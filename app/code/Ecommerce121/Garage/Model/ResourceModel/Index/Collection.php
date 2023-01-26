<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model\ResourceModel\Index;

use Ecommerce121\Garage\Model\ResourceModel\Index as IndexResource;
use Ecommerce121\Garage\Model\DropdownIndex as IndexModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    // @codingStandardsIgnoreLine
    protected function _construct()
    {
        $this->_init(IndexModel::class, IndexResource::class);
    }
}
