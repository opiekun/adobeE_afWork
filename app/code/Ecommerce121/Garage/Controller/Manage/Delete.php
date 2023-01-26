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

class Delete implements ActionInterface
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
            $vehicle = $this->vehicleRepository->getVehicleById((int) $this->request->getParam('vehicle_id'));

            $this->validateVehicle($vehicle);
            $this->vehicleRepository->delete($vehicle);
            $this->messageManager->addSuccessMessage(__('The vehicle has been deleted.'));
            $result->setData(['success' => true]);
        } catch (LocalizedException $e) {
            $this->logger->error($e->getMessage());
            $this->messageManager->addErrorMessage(
                __('Something went wrong while deleting your vehicle. Please, try again later.')
            );
            $result->setData(['success' => false]);
        }

        return $result;
    }

    /**
     * @param VehicleInterface $vehicle
     * @throws LocalizedException
     */
    private function validateVehicle(VehicleInterface $vehicle)
    {
        if ($this->customerSession->getCustomerId() !== $vehicle->getCustomerId()) {
            throw new LocalizedException(__('Wrong customer id'));
        }
    }
}
