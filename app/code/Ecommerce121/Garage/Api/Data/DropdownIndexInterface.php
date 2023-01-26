<?php

namespace Ecommerce121\Garage\Api\Data;

interface DropdownIndexInterface
{
    const INDEX_ID = 'index_id';
    const VALUE_ID = 'value_id';
    const PARENT_ID = 'parent_id';
    const DROPDOWN_ID = 'dropdown_id';
    const NAME = 'name';

    /**
     * @return int
     */
    public function getIndexId();

    /**
     * @param int $indexId
     *
     * @return DropdownIndexInterface
     */
    public function setIndexId($indexId);

    /**
     * @return int
     */
    public function getValueId();

    /**
     * @param int $valueId
     *
     * @return DropdownIndexInterface
     */
    public function setValueId($valueId);

    /**
     * @return int
     */
    public function getParentId();

    /**
     * @param int $parentId
     *
     * @return DropdownIndexInterface
     */
    public function setParentId(int $parentId);

    /**
     * @return int
     */
    public function getDropdownId();

    /**
     * @param int $dropdownId
     *
     * @return DropdownIndexInterface
     */
    public function setDropdownId($dropdownId);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     *
     * @return DropdownIndexInterface
     */
    public function setName($name);
}
