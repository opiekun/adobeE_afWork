<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model;

use Ecommerce121\Garage\Model\Store\Config;
use Amasty\Finder\Model\ResourceModel\Dropdown\CollectionFactory as DropdownCollectionFactory;
use Amasty\Finder\Model\ResourceModel\Value\CollectionFactory as ValueCollectionFactory;

class Dropdown
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
    private $dropdowns;

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
     * @param $dropdown
     */
    public function getParent($dropdown, $value, bool $excludeHideInMenuItems = false)
    {
        if (!isset($this->dropdowns)) {
            $this->loadDropdowns();
        }
        $useHiddenParent = false;
        //if dropdown id has a parent dropdown that is hidden
        //get parent id of the hidden dropdown
        $dropdownParent = $this->getDropdownParent($dropdown);
        if ($dropdownParent != $dropdown) {
            $useHiddenParent = true;
        }

        $parentId = $useHiddenParent ?
            $this->loadHiddenParentId((int)$value->getParentId(), $dropdownParent) : $value->getParentId();

        return $parentId;
    }

    /**
     * @return void
     */
    public function loadDropdowns()
    {
        $dropdownCollection = $this->dropdownCollectionFactory->create();
        $dropdownCollection->addFieldToFilter('finder_id', ['eq' => $this->config->getDefaultFinderId()]);
        $i = 0;
        foreach ($dropdownCollection as $dropdown) {
            $this->dropdowns[$i]['dropdown_id'] = $dropdown->getDropdownId();
            $this->dropdowns[$i]['hide_in_form'] = $dropdown->getHideInForm();
            $i++;
        }
    }

    private function getDropdownParent($dropdownId)
    {
        $index = array_search($dropdownId, array_column($this->dropdowns, 'dropdown_id'));
        while ($index > 0 && $index < count($this->dropdowns) && $this->dropdowns[$index - 1]['hide_in_form'] == 1) {
            $dropdownId = $this->dropdowns[$index - 2]['dropdown_id'];
            $index--;
        }
        return $dropdownId;
    }

    private function loadHiddenParentId($parentId, $dropdownId)
    {
        $valueCollection = $this->valueCollectionFactory->create();
        $valueCollection->addFieldToFilter('value_id', $parentId);
        $valueCollection->addFieldToSelect('parent_id');
        $valueCollection->addFieldToSelect('dropdown_id');
        $valueCollection->addFieldToSelect('value_id');

        while ($valueCollection->getFirstItem()->getDropdownId() !== $dropdownId) {
            $valueCollection = $this->valueCollectionFactory->create();
            $valueCollection->addFieldToFilter('value_id', $parentId);
            $valueCollection->addFieldToSelect('parent_id');
            $valueCollection->addFieldToSelect('dropdown_id');
            $valueCollection->addFieldToSelect('value_id');

            $valueId = $valueCollection->getFirstItem()->getValueId();
            $parentId = $valueCollection->getFirstItem()->getParentId();
        }
        return $valueId;
    }
}
