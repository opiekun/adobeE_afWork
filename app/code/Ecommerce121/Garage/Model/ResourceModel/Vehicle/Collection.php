<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model\ResourceModel\Vehicle;

use Ecommerce121\Garage\Model\ResourceModel\Vehicle as VehicleResource;
use Ecommerce121\Garage\Model\Vehicle as VehicleModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    // @codingStandardsIgnoreLine
    protected function _construct()
    {
        $this->_init(VehicleModel::class, VehicleResource::class);
    }
}
