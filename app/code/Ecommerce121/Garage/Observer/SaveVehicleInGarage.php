<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Observer;

use Ecommerce121\Garage\Api\Data\VehicleInterfaceFactory;
use Ecommerce121\Garage\Api\VehicleRepositoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

class SaveVehicleInGarage implements ObserverInterface
{
    /**
     * @var VehicleInterfaceFactory
     */
    private $vehicleFactory;

    /**
     * @var VehicleRepositoryInterface
     */
    private $vehicleRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param VehicleInterfaceFactory $vehicleFactory
     * @param VehicleRepositoryInterface $vehicleRepository
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     */
    public function __construct(
        VehicleInterfaceFactory $vehicleFactory,
        VehicleRepositoryInterface $vehicleRepository,
        RequestInterface $request,
        LoggerInterface $logger
    ) {
        $this->vehicleFactory = $vehicleFactory;
        $this->vehicleRepository = $vehicleRepository;
        $this->request = $request;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     */
    public function execute(Observer $observer)
    {
        $values = $this->getValues();
        if (!$values) {
            return $this;
        }

        try {
            $vehicle = $this->vehicleFactory->create();
            $vehicle->setCustomerId((string) $observer->getCustomer()->getId());
            $vehicle->setValues($values);
            $this->vehicleRepository->save($vehicle);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return $this;
    }

    /**
     * @return array
     */
    private function getValues(): array
    {
        $values = [];
        $parts = $this->request->getParam('garage_vehicle', []);
        if (!is_array($parts)) {
            return $values;
        }

        foreach ($parts as $dropdownId => $valueId) {
            if ($valueId) {
                $values[$dropdownId] = $valueId;
            }
        }

        return $values;
    }
}
