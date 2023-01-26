<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Plugin;

use Amasty\Finder\Api\Data\ValueInterface;
use Amasty\Finder\Model\Dropdown;
use Amasty\Finder\Model\Repository\ValueRepository;
use Amasty\Finder\Model\ResourceModel\Value;
use Amasty\Finder\Model\Session as FinderSession;
use Amasty\Finder\Model\Finder;
use Ecommerce121\Garage\Model\ResourceModel\Index;

class ReindexAfterUpdateValue
{
    /**
     * @var Dropdown
     */
    private $dropdownIndexer;

    /**
     * @var Value
     */
    private $valueResource;

    /**
     * @var Index
     */
    private $indexResource;

    /**
     * @param FinderSession $finderSession
     * @param \Ecommerce121\Garage\Model\Indexer\Dropdown $dropdownIndexer
     */
    public function __construct(
        FinderSession $finderSession,
        \Ecommerce121\Garage\Model\Indexer\Dropdown $dropdownIndexer,
        Value $valueResource,
        Index $indexResource
    ) {
        $this->dropdownIndexer = $dropdownIndexer;
        $this->valueResource = $valueResource;
        $this->indexResource = $indexResource;
    }

    /**
     * @param ValueRepository $subject
     * @param $result
     * @param ValueInterface $value
     * @return mixed
     */
    public function afterSave(ValueRepository $subject, $result, ValueInterface $value)
    {
        if ($this->dropdownIndexer->isIndexerScheduled()) {
            $this->dropdownIndexer->invalidateIndex();
        } else {
            $this->dropdownIndexer->executeByValueId($result->getValueId());
        }
        return $result;
    }

    /**
     * @param ValueRepository $subject
     * @param $result
     * @param $parentId
     * @param $dropdownId
     * @param $name
     * @return mixed
     */
    public function afterSaveValue(ValueRepository $subject, $result, $parentId, $dropdownId, $name)
    {
        if ($this->dropdownIndexer->isIndexerScheduled()) {
            $this->dropdownIndexer->invalidateIndex();
        } else {
            $connection = $this->valueResource->getConnection();

            $select = $connection->select();
            $select->from($this->valueResource->getTable('amasty_finder_value'))
                ->where('dropdown_id =?', $dropdownId)
                ->where('parent_id =?', $parentId)
                ->where('name =?', $name);
            $savedValue = $this->valueResource->getConnection()->fetchRow($select);

            $this->dropdownIndexer->executeByValueId($savedValue['value_id']);
        }
        return $result;
    }

    /**
     * @param ValueRepository $subject
     * @param $result
     * @param $id
     * @param $finder
     * @return void
     */
    public function afterDeleteById(ValueRepository $subject, $result, $id, $finder)
    {
        $this->indexResource->cleanByValueIds([$id]);

        return $result;
    }
}
