<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model\ResourceModel;

use Amasty\Finder\Helper\Import as ImportHelper;
use Ecommerce121\Garage\Model\Store\Config;
use Amasty\Finder\Model\ResourceModel\Dropdown\CollectionFactory as DropdownCollectionFactory;
use Amasty\Finder\Model\ResourceModel\Dropdown\Collection as DropdownCollection;
use Amasty\Finder\Model\ResourceModel\Value\CollectionFactory as ValueCollectionFactory;
use Ecommerce121\Garage\Model\ResourceModel\Index\CollectionFactory as IndexedValueCollectionFactory;

class DropdownData
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var DropdownCollectionFactory
     */
    private $dropdownCollectionFactory;

    /**
     * @var ValueCollectionFactory
     */
    private $valueCollectionFactory;

    /**
     * @var $dropdownCollection
     */
    private $dropdownCollection;

    /**
     * @var IndexedValueCollectionFactory
     */
    private $indexedValueCollectionFactory;

    /**
     * @param Config $config
     * @param DropdownCollectionFactory $dropdownCollectionFactory
     * @param ValueCollectionFactory $valueCollectionFactory
     */
    public function __construct(
        Config $config,
        DropdownCollectionFactory $dropdownCollectionFactory,
        ValueCollectionFactory $valueCollectionFactory,
        IndexedValueCollectionFactory $indexedValueCollectionFactory
    ) {
        $this->config = $config;
        $this->dropdownCollectionFactory = $dropdownCollectionFactory;
        $this->valueCollectionFactory = $valueCollectionFactory;
        $this->indexedValueCollectionFactory = $indexedValueCollectionFactory;
    }

    /**
     * @param bool $excludeHideInMenuItems
     * @return array
     */
    public function getDropdownData($id, bool $excludeHideInMenuItems = false): array
    {
        $dropdownCollection = $this->getDropdownCollection();
        $dropdownCollection
            ->addFieldToFilter('dropdown_id', (int) $id)
            ->getItems();

        $values = [];
        $dropdown = $this->dropdownCollection->getFirstItem();
        $values = $this->getDropdownValues($dropdown);

        return $values;
    }

    /**
     * @param $dropdown
     * @return array
     */
    public function getDropdownValues($dropdown): array
    {
        $values = [];
        $dropdownId = $dropdown->getId();
        $indexedValueCollection = $this->indexedValueCollectionFactory->create();
        $indexedValueCollection
            ->addFieldToFilter('name', ['neq' => null])
            ->addFieldToFilter('dropdown_id', $dropdownId);
        $indexedValueCollection->getSelect()->order($this->getOrder((int) $dropdown->getSort()));

        foreach ($indexedValueCollection->getItems() as $item) {
            $values[$dropdownId][] = [
                'dropdown_id' => $dropdownId,
                'value' => $item->getName(),
                'parent_id' => $item->getParentId(),
                'value_id' => $item->getValueId(),
            ];
        }

        return $values;
    }

    /**
     * @param int $sort
     * @return string
     */
    private function getOrder(int $sort): string
    {
        switch ($sort) {
            case ImportHelper::SORT_STRING_DESC:
                return 'name DESC';
            case ImportHelper::SORT_NUM_ASC:
                return 'CAST(`name` AS DECIMAL(10,2)) ASC';
            case ImportHelper::SORT_NUM_DESC:
                return 'CAST(`name` AS DECIMAL(10,2)) DESC';
            default:
                return 'name ASC';
        }
    }

    /**
     * @return DropdownCollection
     */
    private function getDropdownCollection(): DropdownCollection
    {
        if (!isset($this->dropdownCollection)) {
            $this->dropdownCollection = $this->dropdownCollectionFactory->create();
            $this->dropdownCollection->addFieldToFilter('finder_id', ['eq' => $this->config->getDefaultFinderId()]);
        }

        return $this->dropdownCollection;
    }
}
