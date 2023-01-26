<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Setup\Patch\Data\Model;

use Ecommerce121\Garage\Api\Data\VehicleInterfaceFactory;
use Ecommerce121\Garage\Api\VehicleRepositoryInterface;

class ImportGaragesDataProcessor
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
     * @param VehicleInterfaceFactory $vehicleFactory
     * @param VehicleRepositoryInterface $vehicleRepository
     */
    public function __construct(VehicleInterfaceFactory $vehicleFactory, VehicleRepositoryInterface $vehicleRepository)
    {
        $this->vehicleFactory = $vehicleFactory;
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * @param array $data
     */
    public function process(array $data)
    {
        foreach ($data as $item) {
            $vehicle = $this->vehicleFactory->create();
            $vehicle->setCustomerId((string) $item['customer_id']);
            $vehicle->setValues($item['values']);

            $this->vehicleRepository->save($vehicle);
        }
    }
}
