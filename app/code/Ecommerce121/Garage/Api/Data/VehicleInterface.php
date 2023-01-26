<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Api\Data;

interface VehicleInterface
{
    /**
     * @param string $id
     * @return VehicleInterface
     */
    public function setVehicleId(string $id): VehicleInterface;

    /**
     * @return string|null
     */
    public function getVehicleId(): ?string;

    /**
     * @param string $customerId
     * @return VehicleInterface
     */
    public function setCustomerId(string $customerId): VehicleInterface;

    /**
     * @return string
     */
    public function getCustomerId(): string;

    /**
     * @param array $values
     * @return VehicleInterface
     */
    public function setValues(array $values): VehicleInterface;

    /**
     * @return array|null
     */
    public function getValues(): ?array;
}
