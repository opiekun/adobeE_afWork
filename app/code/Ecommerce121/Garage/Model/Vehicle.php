<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model;

use Ecommerce121\Garage\Api\Data\VehicleInterface;
use Ecommerce121\Garage\Model\ResourceModel\Vehicle as VehicleResource;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Vehicle extends AbstractModel implements IdentityInterface, VehicleInterface
{
    const CACHE_TAG = 'ecommerce121_garage_vehicle';

    /**
     * @inheridoc
     */
    // @codingStandardsIgnoreLine
    protected function _construct()
    {
        $this->_init(VehicleResource::class);
    }

    /**
     * @inheridoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheridoc
     */
    public function setVehicleId(string $id): VehicleInterface
    {
        $this->setId($id);
        return $this;
    }

    /**
     * @inheridoc
     */
    public function getVehicleId(): ?string
    {
        return $this->getId();
    }

    /**
     * @inheridoc
     */
    public function setCustomerId(string $customerId): VehicleInterface
    {
        $this->setData('customer_id', $customerId);
        return $this;
    }

    /**
     * @inheridoc
     */
    public function getCustomerId(): string
    {
        return $this->getData('customer_id');
    }

    /**
     * @inheridoc
     */
    public function setValues(array $values): VehicleInterface
    {
        $this->setData('values', $values);
        return $this;
    }

    /**
     * @inheridoc
     */
    public function getValues(): ?array
    {
        return $this->getData('values');
    }
}
