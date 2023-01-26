<?php

declare(strict_types=1);

namespace Ecommerce121\SalesPartialShipments\Cron;

use Ecommerce121\SalesPartialShipments\Constants;
use Ecommerce121\SalesPartialShipments\Model\Store\Config\Config;
use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\ShipmentFactory;
use Magento\Sales\Model\Order\ShipmentRepository;
use Magento\Sales\Model\Service\InvoiceService;
use Magento\Framework\DB\Transaction;
use Magento\Sales\Model\Order\Email\Sender\InvoiceSender;
use Psr\Log\LoggerInterface;
use Magento\Sales\Model\Convert\Order as ConvertOrder;
use Magento\Sales\Api\OrderStatusHistoryRepositoryInterface;

class SetOrdersCompleteCronjob
{
    /**
     * @var SearchCriteriaBuilder
     */
    private SearchCriteriaBuilder $searchCriteriaBuilder;

    /**
     * @var OrderRepositoryInterface
     */
    private OrderRepositoryInterface $orderRepository;

    /**
     * @var ShipmentRepository
     */
    private ShipmentRepository $shipmentRepository;

    /**
     * @var ShipmentFactory
     */
    private ShipmentFactory $shipmentFactory;

    /**
     * @var InvoiceService
     */
    private InvoiceService $invoiceService;

    /**
     * @var InvoiceSender
     */
    private InvoiceSender $invoiceSender;

    /**
     * @var Transaction
     */
    private Transaction $transaction;

    /**
     * @var Config
     */
    private Config $config;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var ConvertOrder
     */
    private ConvertOrder $convertOrder;

    /**
     * @var OrderStatusHistoryRepositoryInterface
     */
    private OrderStatusHistoryRepositoryInterface $orderStatusRepository;

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ShipmentRepository $shipmentRepository
     * @param ShipmentFactory $shipmentFactory
     * @param OrderRepositoryInterface $orderRepository
     * @param InvoiceService $invoiceService
     * @param InvoiceSender $invoiceSender
     * @param Transaction $transaction
     * @param Config $config
     * @param LoggerInterface $logger
     * @param ConvertOrder $convertOrder
     * @param OrderStatusHistoryRepositoryInterface $orderStatusRepository
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ShipmentRepository $shipmentRepository,
        ShipmentFactory $shipmentFactory,
        OrderRepositoryInterface $orderRepository,
        InvoiceService $invoiceService,
        InvoiceSender $invoiceSender,
        Transaction $transaction,
        Config $config,
        LoggerInterface $logger,
        ConvertOrder $convertOrder,
        OrderStatusHistoryRepositoryInterface $orderStatusRepository
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->orderRepository = $orderRepository;
        $this->shipmentRepository = $shipmentRepository;
        $this->shipmentFactory = $shipmentFactory;
        $this->invoiceService = $invoiceService;
        $this->invoiceSender = $invoiceSender;
        $this->transaction = $transaction;
        $this->config = $config;
        $this->logger = $logger;
        $this->convertOrder = $convertOrder;
        $this->orderStatusRepository = $orderStatusRepository;
    }

    /**
     * @return void
     * @throws CouldNotSaveException
     */
    public function execute(): void
    {
        if ($this->config->isEnabled()) {
            $searchCriteria = $this->searchCriteriaBuilder->addFilter(
                OrderInterface::STATUS,
                [
                    Constants::ORDER_STATUS_PARTIALLY_SHIPPED_CODE
                ],
                'in'
            )->create();

            $orders = $this->orderRepository->getList($searchCriteria);
            foreach ($orders->getItems() as $order) {
                try {
                    $this->createInvoice($order);
                    $this->createShipment($order);

                    $order->setState(Order::STATE_COMPLETE);
                    $order->setStatus(Order::STATE_COMPLETE);

                    $this->orderRepository->save($order);

                } catch (CouldNotSaveException $e) {
                    $errorLog = 'Cron SetOrdersCompleteCronjob - CouldNotSaveException: ' . $e->getLogMessage();
                    $this->logger->info($errorLog);
                }
            }
        } else {
            $this->logger->info(
                'Cron SetOrdersCompleteCronjob executed but disabled via configuration.'
            );
        }
    }

    /**
     * @param Order $order
     * @throws CouldNotSaveException
     */
    protected function createInvoice(Order $order)
    {
        if ($order->canInvoice()) {
            try {
                $invoice = $this->invoiceService->prepareInvoice($order);
                $invoice->setRequestedCaptureCase(Invoice::CAPTURE_ONLINE);
                $invoice->register();
                $invoice->save();

                $transactionSave =
                    $this->transaction
                        ->addObject($invoice)
                        ->addObject($invoice->getOrder());

                $transactionSave->save();
                $this->invoiceSender->send($invoice);

            } catch (Exception $e) {
                $errorLog = 'Error while creating an invoice in cron SetOrdersCompleteCronjob: ' . $e->getMessage();
                $this->logger->info($errorLog);
                $comment = $order->addCommentToStatusHistory($errorLog);
                $this->orderStatusRepository->save($comment);
            }
        }
    }

    /**
     * @param Order $order
     * @throws CouldNotSaveException
     */
    protected function createShipment(Order $order)
    {
        if ($order->canShip()) {
            try {
                $shipment = $this->prepareShipment($order);
                $this->shipmentRepository->save($shipment);
            } catch (CouldNotSaveException|InputException|LocalizedException $e) {
                $errorLog = 'Error while creating a shipment in cron to set complete: ' . $e->getMessage();
                $this->logger->info($errorLog);
                $comment = $order->addCommentToStatusHistory($errorLog);
                $this->orderStatusRepository->save($comment);
            }
        }
    }

    /**
     * @param $order
     * @return Order\Shipment
     * @throws InputException
     * @throws LocalizedException
     */
    private function prepareShipment($order): Order\Shipment
    {
        $shipment = $this->convertOrder->toShipment($order);
        foreach ($order->getAllItems() as $orderItem) {
            if ($orderItem->getIsVirtual()) {
                continue;
            }
            $qtyShipped = $orderItem->getQtyOrdered();
            $shipmentItem
                = $this->convertOrder->itemToShipmentItem($orderItem)
                ->setQty($qtyShipped);
            $shipment->addItem($shipmentItem);
        }

        $shipment->register();
        $shipment->getOrder()->setIsInProcess(true);

        return $shipment;
    }
}
