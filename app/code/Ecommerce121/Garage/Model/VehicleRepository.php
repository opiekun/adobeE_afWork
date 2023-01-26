<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model;

use Ecommerce121\Garage\Api\Data\VehicleInterface;
use Ecommerce121\Garage\Api\Data\VehicleInterfaceFactory;
use Ecommerce121\Garage\Api\VehicleRepositoryInterface;
use Ecommerce121\Garage\Model\ResourceModel\Vehicle as VehicleResource;
use Ecommerce121\Garage\Model\ResourceModel\Vehicle\Collection as GarageCollection;
use Ecommerce121\Garage\Model\ResourceModel\Vehicle\CollectionFactory as VehicleCollectionFactory;
use Magento\Framework\Exception\AlreadyExistsException;

class VehicleRepository implements VehicleRepositoryInterface
{
    /**
     * @var VehicleInterfaceFactory
     */
    private $vehicleFactory;

    /**
     * @var VehicleResource
     */
    private $vehicleResource;

    /**
     * @var VehicleCollectionFactory
     */
    private $vehicleCollectionFactory;

    /**
     * @param VehicleInterfaceFactory $vehicleFactory
     * @param VehicleResource $vehicleResource
     * @param VehicleCollectionFactory $vehicleCollectionFactory
     */
    public function __construct(
        VehicleInterfaceFactory $vehicleFactory,
        VehicleResource $vehicleResource,
        VehicleCollectionFactory $vehicleCollectionFactory
    ) {
        $this->vehicleFactory = $vehicleFactory;
        $this->vehicleResource = $vehicleResource;
        $this->vehicleCollectionFactory = $vehicleCollectionFactory;
    }

    /**
     * @param int $id
     * @return VehicleInterface
     */
    public function getVehicleById(int $id): VehicleInterface
    {
        $vehicle = $this->vehicleFactory->create();
        $this->vehicleResource->load($vehicle, $id);

        return $vehicle;
    }

    /**
     * @param VehicleInterface $vehicle
     * @return VehicleRepositoryInterface
     * @throws AlreadyExistsException
     */
    public function save(VehicleInterface $vehicle): VehicleRepositoryInterface
    {
        $this->vehicleResource->save($vehicle);
        return $this;
    }

    /**
     * @param VehicleInterface $vehicle
     * @return VehicleRepositoryInterface
     * @throws \Exception
     */
    public function delete(VehicleInterface $vehicle): VehicleRepositoryInterface
    {
        $this->vehicleResource->delete($vehicle);
        return $this;
    }

    /**
     * @param int $customerId
     * @return array
     */
    public function getVehiclesForCustomer(int $customerId): array
    {
        $collection = $this->vehicleCollectionFactory->create();
        $collection->addFieldToFilter('customer_id', ['eq' => $customerId]);

        return $this->getVehiclesWithValues($collection);
    }

    /**
     * @param GarageCollection $collection
     * @return array
     */
    private function getVehiclesWithValues(GarageCollection $collection): array
    {
        $values = $this->vehicleResource->getValues($collection->getAllIds());
        $vehicles = $collection->getItems();

        foreach ($vehicles as $vehicle) {
            $vehicle->setValues($values[$vehicle->getId()] ?? []);
        }

        return $vehicles;
    }
}
