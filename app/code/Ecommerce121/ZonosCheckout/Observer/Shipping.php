<?php

declare(strict_types=1);

namespace Ecommerce121\ZonosCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\StateException;
use Zonos\Checkout\Api\Data\ZonosSalesOrderInterface;
use Zonos\Checkout\Helper\Rest;
use Zonos\Checkout\Model\ResourceModel\ZonosSalesOrderResourceModel;
use Zonos\Checkout\Model\Api\Data\ZonosSalesOrderFactory;
use Zonos\Checkout\Observer\Shipping as ZonosShippingObserver;

class Shipping extends ZonosShippingObserver
{
    const ORDER_COULD_NOT_BE_SHIPPED_ORDER_IS_SHOWING_CANCELED = 'Order could not be shipped: Order is showing canceled in app.zonos.com.';
    const ORDER_COULD_NOT_BE_SHIPPED_ORDER_IS_ON_HOLD = 'Order could not be shipped: Order is on hold in iGlobal, please check app.zonos.com';
    const ORDER_COULD_NOT_BE_SHIPPED_ORDER_IS_UNDERGOING_FRAUD_REVIEW = 'Order could not be shipped: Order is still undergoing fraud review. See status in app.zonos.com.';

    /**
     * @var ZonosSalesOrderFactory
     */
    protected $zonosSalesOrderFactory;

    /**
     * @var ZonosSalesOrderResourceModel
     */
    protected $zonosSalesOrderResourceModel;

    /**
     * @var Rest
     */
    protected $zonosApi;

    /**
     * @param ZonosSalesOrderFactory $zonosSalesOrderFactory
     * @param ZonosSalesOrderResourceModel $zonosSalesOrderResourceModel
     * @param Rest $zonosApi
     */
    public function __construct(
        ZonosSalesOrderFactory $zonosSalesOrderFactory,
        ZonosSalesOrderResourceModel $zonosSalesOrderResourceModel,
        Rest $zonosApi
    ) {
        parent::__construct(
            $zonosSalesOrderFactory,
            $zonosSalesOrderResourceModel,
            $zonosApi
        );
    }

    /**
     * @param Observer $observer
     * @return void
     * @throws StateException
     */
    public function execute(Observer $observer)
    {
        $orderId = $observer->getEvent()->getShipment()->getOrderId();

        if ($orderId !== null) {
            /** @var ZonosSalesOrderInterface   $zonosOrder */
            $zonosOrder = $this->zonosSalesOrderFactory->create();
            $this->zonosSalesOrderResourceModel->loadByMagentoId($zonosOrder, (int)$orderId);

            if ($zonosOrder->getData()) {
                if ($zonosOrder->getZonosOrderId() !== null) {
                    $zonosApiOrder = $this->zonosApi->getOrder($zonosOrder->getZonosOrderId())->order;

                    switch($zonosApiOrder->orderStatus){
                        case 'IGLOBAL_FRAUD_REVIEW';
                            throw new StateException(
                                __(self::ORDER_COULD_NOT_BE_SHIPPED_ORDER_IS_UNDERGOING_FRAUD_REVIEW)
                            );
                            break;
                        case 'IGLOBAL_ORDER_ON_HOLD';
                            throw new StateException(
                                __(self::ORDER_COULD_NOT_BE_SHIPPED_ORDER_IS_ON_HOLD)
                            );
                            break;
                        case 'IGLOBAL_ORDER_CANCELLED';
                            throw new StateException(
                                __(self::ORDER_COULD_NOT_BE_SHIPPED_ORDER_IS_SHOWING_CANCELED)
                            );
                            break;
                    }
                }
            }
        }
    }
}
