<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model\ResourceModel;

use Ecommerce121\Garage\Api\Data\DropdownIndexInterface;
use Amasty\Finder\Api\Data\ValueInterface as ValueInterface;
use Ecommerce121\Garage\Model\DropdownIndex;
use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Index extends AbstractDb
{
    private const AMASTY_FINDER_VALUE_INDEX_TABLE = 'ecommerce121_amasty_finder_value_index';

    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::AMASTY_FINDER_VALUE_INDEX_TABLE, 'index_id');
    }

    /**
     * @param $valuesIds
     */
    public function cleanByValueIds($valueIds)
    {
        $query = $this->getConnection()->deleteFromSelect(
            $this->getConnection()
                ->select()
                ->from($this->getMainTable(), ValueInterface::VALUE_ID)
                ->where(ValueInterface::VALUE_ID . ' IN (?)', $valueIds),
            $this->getMainTable()
        );

        $this->getConnection()->query($query);
    }

    /**
     * @param $dropdownIds
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function cleanByDropdownIds($dropdownIds)
    {
        $query = $this->getConnection()->deleteFromSelect(
            $this->getConnection()
                ->select()
                ->from($this->getMainTable(), ValueInterface::VALUE_ID)
                ->where(DropdownIndexInterface::DROPDOWN_ID . ' IN (?)', $dropdownIds),
            $this->getMainTable()
        );

        $this->getConnection()->query($query);
    }

    /**
     * @param $valueId
     * @return int|void
     */
    public function getCountValueIndexes($valueId)
    {
        return $this->getCountIndex($valueId);
    }

    /**
     * @param int|string $valueId
     * @param bool $guest
     * @return int|void
     */
    public function getCountIndex($valueId)
    {
        $query = $this->getConnection()
            ->select()
            ->from($this->getMainTable(), ValueInterface::VALUE_ID)
            ->where(ValueInterface::VALUE_ID . ' IN (?)', $valueId);

        return count($this->getConnection()->fetchAll($query));
    }

    /**
     * @return $this
     */
    public function cleanAllIndex()
    {
        $this->getConnection()->truncateTable($this->getMainTable());

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function insertIndexData(array $data): Index
    {
        $this->getConnection()->insertOnDuplicate($this->getMainTable(), $data);

        return $this;
    }
}
