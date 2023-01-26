<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Controller\Manage;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Ecommerce121\Garage\Api\VehicleRepositoryInterface;
use Ecommerce121\Garage\Api\Data\VehicleInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Controller\Result\JsonFactory as JsonResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Psr\Log\LoggerInterface;

class Save implements ActionInterface
{
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var VehicleRepositoryInterface
     */
    private $vehicleRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var JsonResultFactory
     */
    private $jsonResultFactory;

    /**
     * @var MessageManager
     */
    private $messageManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param CustomerSession $customerSession
     * @param VehicleRepositoryInterface $vehicleRepository
     * @param RequestInterface $request
     * @param JsonResultFactory $jsonResultFactory
     * @param MessageManager $messageManager
     * @param LoggerInterface $logger
     */
    public function __construct(
        CustomerSession $customerSession,
        VehicleRepositoryInterface $vehicleRepository,
        RequestInterface $request,
        JsonResultFactory $jsonResultFactory,
        MessageManager $messageManager,
        LoggerInterface $logger
    ) {
        $this->customerSession = $customerSession;
        $this->vehicleRepository = $vehicleRepository;
        $this->request = $request;
        $this->jsonResultFactory = $jsonResultFactory;
        $this->messageManager = $messageManager;
        $this->logger = $logger;
    }

    /**
     * @inheridoc
     */
    public function execute()
    {
        $result = $this->jsonResultFactory->create();
        try {
            $vehicles = $this->request->getParam('vehicles');
            if (!is_array($vehicles)) {
                throw new LocalizedException(__('Wrong request parasm'));
            }

            foreach ($vehicles as $vehicle) {
                $this->saveVehicle($vehicle);
            }

            $this->messageManager->addSuccessMessage(__('My Garage information has been saved.'));
            $result->setData(['success' => true]);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(
                __('Something went wrong while saving your garage. Please, try again later.')
            );
            $result->setData(['success' => false]);
        }

        return $result;
    }

    /**
     * @param VehicleInterface $vehicle
     * @return bool
     */
    private function isValid(VehicleInterface $vehicle): bool
    {
        return $this->customerSession->getCustomerId() == $vehicle->getCustomerId();
    }

    /**
     * @param array $vehicle
     */
    private function saveVehicle(array $vehicle)
    {
        $vehicleId = $vehicle['vehicle_id'] ?? '';
        $values = $vehicle['values'] ?? [];
        if (!$vehicleId && !$values) {
            return;
        }

        $vehicle = $this->vehicleRepository->getVehicleById((int) $vehicleId);
        if (!$vehicle->getVehicleId()) {
            $vehicle->setCustomerId((string) $this->customerSession->getCustomerId());
        }

        if (!$this->isValid($vehicle)) {
            return;
        }

        $vehicle->setValues($values);
        $this->vehicleRepository->save($vehicle);
    }
}
