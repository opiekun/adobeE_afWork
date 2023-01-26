<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model\ResourceModel;

use Amasty\Finder\Helper\Import as ImportHelper;
use Ecommerce121\Garage\Model\Store\Config;
use Amasty\Finder\Model\ResourceModel\Dropdown\CollectionFactory as DropdownCollectionFactory;
use Amasty\Finder\Model\ResourceModel\Dropdown\Collection as DropdownCollection;
use Amasty\Finder\Model\ResourceModel\Value\CollectionFactory as ValueCollectionFactory;

class DefaultFormValues
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
     * @var array
     */
    private $hiddenValues = [];

    /**
     * @param Config $config
     * @param DropdownCollectionFactory $dropdownCollectionFactory
     * @param ValueCollectionFactory $valueCollectionFactory
     */
    public function __construct(
        Config $config,
        DropdownCollectionFactory $dropdownCollectionFactory,
        ValueCollectionFactory $valueCollectionFactory
    ) {
        $this->config = $config;
        $this->dropdownCollectionFactory = $dropdownCollectionFactory;
        $this->valueCollectionFactory = $valueCollectionFactory;
    }

    /**
     * @param bool $excludeHideInMenuItems
     * @return array
     */
    public function getFormDropdowns(bool $excludeHideInMenuItems = false): array
    {
        $dropdownCollection = $this->getDropdownCollection();
        if ($excludeHideInMenuItems) {
            $dropdownCollection->addFieldToFilter('hide_in_form', 0);
        }

        $values = [];
        foreach ($dropdownCollection->getItems() as $item) {
            $values[] = [
                'dropdown_id' => $item->getDropdownId(),
                'label' => $item->getName(),
                'required' => (bool) $item->getRequired(),
            ];
        }

        return $values;
    }

    /**
     * @param bool $excludeHideInMenuItems
     * @return array
     */
    public function getFormValues(bool $excludeHideInMenuItems = false): array
    {
        $dropdownCollection = $this->getDropdownCollection();
        if ($excludeHideInMenuItems) {
            $this->loadHiddenValues($dropdownCollection->getAllIds());
        }

        $values = [];
        foreach ($dropdownCollection->getItems() as $dropdown) {
            $dropdownId = $dropdown->getId();
            $valueCollection = $this->valueCollectionFactory->create();
            $valueCollection
                ->addFieldToFilter('name', ['neq' => null])
                ->addFieldToFilter('dropdown_id', $dropdownId);

            $valueCollection->getSelect()
                ->order($this->getOrder((int) $dropdown->getSort()));

            foreach ($valueCollection->getItems() as $item) {
                $values[$dropdownId][] = [
                    'dropdown_id' => $dropdownId,
                    'value' => $item->getName(),
                    'parent_id' => (string) $this->getParentId((int) $item->getParentId()),
                    'value_id' => $item->getValueId(),
                ];
            }
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
        $dropdownCollection = $this->dropdownCollectionFactory->create();
        $dropdownCollection->addFieldToFilter('finder_id', ['eq' => $this->config->getDefaultFinderId()]);

        return $dropdownCollection;
    }

    /**
     * @param int $parentId
     * @return int
     */
    private function getParentId(int $parentId): int
    {
        if (!isset($this->hiddenValues[$parentId])) {
            return $parentId;
        }

        return $this->getParentId((int) $this->hiddenValues[$parentId]->getParentId());
    }

    /**
     * @param array $dropdownIds
     * @return void
     */
    private function loadHiddenValues(array $dropdownIds)
    {
        $valueCollection = $this->valueCollectionFactory->create();
        $valueCollection->addFieldToFilter('main_table.dropdown_id', ['in' => $dropdownIds]);

        $valueCollection->join(
            ['amasty_finder_dropdown' => $valueCollection->getTable('amasty_finder_dropdown')],
            'amasty_finder_dropdown.dropdown_id=main_table.dropdown_id',
            []
        );

        $valueCollection->addFieldToFilter('amasty_finder_dropdown.hide_in_form', 1);
        $this->hiddenValues = $valueCollection->getItems();
    }
}
