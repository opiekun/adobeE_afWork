<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Api;

use Ecommerce121\Garage\Api\Data\VehicleInterface;

interface VehicleRepositoryInterface
{
    /**
     * @param int $id
     * @return VehicleInterface
     */
    public function getVehicleById(int $id): VehicleInterface;

    /**
     * @param VehicleInterface $vehicle
     * @return VehicleRepositoryInterface
     */
    public function save(VehicleInterface $vehicle): VehicleRepositoryInterface;

    /**
     * @param VehicleInterface $vehicle
     * @return VehicleRepositoryInterface
     */
    public function delete(VehicleInterface $vehicle): VehicleRepositoryInterface;

    /**
     * @param int $customerId
     * @return array
     */
    public function getVehiclesForCustomer(int $customerId): array;
}
