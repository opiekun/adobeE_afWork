<?php

namespace Ecommerce121\Garage\Model;

use Ecommerce121\Garage\Api\Data\DropdownIndexInterface;
use Magento\Framework\Model\AbstractModel;

class DropdownIndex extends AbstractModel implements DropdownIndexInterface
{
    /**
     * {@inheritdoc}
     */
    public function getIndexId()
    {
        return $this->_getData(DropdownIndexInterface::INDEX_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setIndexId($indexId)
    {
        $this->setData(DropdownIndexInterface::INDEX_ID, $indexId);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getValueId()
    {
        return $this->_getData(DropdownIndexInterface::VALUE_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setValueId($valueId)
    {
        $this->setData(DropdownIndexInterface::VALUE_ID, $valueId);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getParentId()
    {
        return $this->_getData(DropdownIndexInterface::PARENT_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setParentId($parentId)
    {
        $this->setData(DropdownIndexInterface::PARENT_ID, $parentId);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDropdownId()
    {
        return $this->_getData(DropdownIndexInterface::DROPDOWN_ID);
    }

    /**
     * {@inheritdoc}
     */
    public function setDropdownId($dropdownId)
    {
        $this->setData(DropdownIndexInterface::DROPDOWN_ID, $dropdownId);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->_getData(DropdownIndexInterface::NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->setData(DropdownIndexInterface::NAME, $name);

        return $this;
    }
}
