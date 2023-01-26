<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model\Indexer;

use Amasty\Finder\Api\Data\ValueInterface;
use Amasty\Finder\Model\ResourceModel\Dropdown\Collection;
use Amasty\Finder\Model\ResourceModel\Value\CollectionFactory as ValueCollectionFactory;
use Ecommerce121\Garage\Api\Data\DropdownIndexInterface;
use Ecommerce121\Garage\Model\ResourceModel\Index;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Exception\LocalizedException;
use Amasty\Finder\Model\ResourceModel\Dropdown\CollectionFactory as DropdownCollectionFactory;
use Psr\Log\LoggerInterface;
use Ecommerce121\Garage\Model\Dropdown;

class IndexBuilder
{
    /**
     * @var Collection|null
     */
    private $dropdownIds = null;

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var AdapterInterface
     */
    private $connection;

    /**
     * @var DropdownCollectionFactory
     */
    private $dropdownCollectionFactory;

    /**
     * @var ValueCollectionFactory
     */
    private $valueCollectionFactory;

    /**
     * @var Index
     */
    private $indexResource;

    /**
     * @var int
     */
    private $batchCount;

    /**
     * @var Dropdown
     */
    private $dropdown;

    public function __construct(
        ResourceConnection $resource,
        LoggerInterface $logger,
        ValueCollectionFactory $valueCollectionFactory,
        DropdownCollectionFactory $dropdownCollectionFactory,
        Index $indexResource,
        Dropdown $dropdown,
        $batchCount = 1000
    ) {
        $this->resource = $resource;
        $this->connection = $resource->getConnection();
        $this->logger = $logger;
        $this->valueCollectionFactory = $valueCollectionFactory;
        $this->dropdownCollectionFactory = $dropdownCollectionFactory;
        $this->indexResource = $indexResource;
        $this->dropdown = $dropdown;
        $this->batchCount = $batchCount;
    }

    /**
     * Full reindex
     *
     * @throws LocalizedException
     * @return void
     * @api
     */
    public function reindexAll()
    {
        try {
            $this->indexResource->cleanAllIndex();
            $this->indexResource->beginTransaction();
            $dropdownIds = $this->getAllDropdownIds();
            $this->reindex($dropdownIds);
            $this->indexResource->commit();
        } catch (\Exception $e) {
            $this->indexResource->rollBack();
            $this->logger->critical($e->getMessage());
            throw new LocalizedException(__($e->getMessage()), $e);
        }
    }

    /**
     * Reindex by dropdown ids
     *
     * @param array $ids
     * @throws LocalizedException
     * @return void
     * @api
     */
    public function reindexByDropdownIds(array $ids)
    {
        $this->indexResource->beginTransaction();
        try {
            $this->cleanByDropdownIds($ids);
            $this->reindex($ids);
            $this->indexResource->commit();
        } catch (\Exception $e) {
            $this->indexResource->rollBack();
            $this->logger->critical($e);
            throw new LocalizedException(
                __("Finder dropdown indexing failed. See details in exception log.")
            );
        }
    }

    /**
     * Reindex by value ids
     *
     * @param array $valueIds
     * @throws LocalizedException
     * @return void
     * @api
     */
    public function reindexByValueIds($valueIds)
    {
        $this->indexResource->beginTransaction();
        try {
            $this->cleanByValueIds($valueIds);
            $dropdownIds = $this->getAllDropdownIds();
            $this->reindex($dropdownIds, $valueIds);
            $this->indexResource->commit();
        } catch (\Exception $e) {
            $this->indexResource->rollBack();
            $this->logger->critical($e);
            throw new LocalizedException(
                __("Amasty value indexing failed. See details in exception log.")
            );
        }
    }

    /**
     * @param $id
     * @throws LocalizedException
     */
    public function reindexByDropdownId($id)
    {
        $ids = $this->getAllDropdownIds();
        if (!is_array($id)) {
            $key = array_search($id, $ids);
            $ids = array_slice($ids, $key);
        }

        $this->reindexByDropdownIds($ids);
    }

    /**
     * @param $id
     * @throws LocalizedException
     */
    public function reindexByValueId($id)
    {
        $this->reindexByValueIds([$id]);
    }

    /**
     * @param $productIds
     * @throws LocalizedException
     */
    private function cleanByDropdownIds($dropdownIds)
    {
        $this->indexResource->cleanByDropdownIds($dropdownIds);
    }

    /**
     * @param $valueIds
     * @throws LocalizedException
     */
    private function cleanByValueIds($valueIds)
    {
        $this->indexResource->cleanByValueIds($valueIds);
    }

    private function reindex($ids, $valueIds = null)
    {
        list($rows, $dropdownIds) = $this->prepareIndexData($ids, $valueIds);

        if (!empty($rows)) {
            $this->indexResource->insertIndexData($rows);
        }

        return $this;
    }

    private function prepareIndexData($ids, $valueIds = null)
    {
        $rows = [];
        $dropdownIds = [];
        $count = 0;

        foreach ($ids as $id) {
            if ($this->filterHiddenDropdowns($id)) {
                continue;
            }
            $valueCollection = $this->valueCollectionFactory->create()->addFieldToFilter('dropdown_id', $id);
            if ($valueIds) {
                $valueCollection->addFieldToFilter(ValueInterface::VALUE_ID, ['in' => $valueIds]);
            }
            foreach ($valueCollection as $value) {
                $parentId = $this->dropdown->getParent($id, $value);

                $rows[] = [
                    DropdownIndexInterface::VALUE_ID => (int)$value->getId(),
                    DropdownIndexInterface::PARENT_ID => $parentId,
                    DropdownIndexInterface::DROPDOWN_ID => $id,
                    DropdownIndexInterface::NAME => $value->getName()
                ];
                $count++;
                $dropdownIds[] = (int)$id;

                if ($count >= $this->batchCount) {
                    $this->indexResource->insertIndexData($rows);
                    $rows = [];
                    $count = 0;
                }
            }
        }

        return [$rows, $dropdownIds];
    }

    /**
     * @return array
     */
    private function getAllDropdownIds()
    {
        if ($this->dropdownIds === null) {
            $this->dropdownIds = $this->dropdownCollectionFactory->create()->getAllIds();
        }

        return $this->dropdownIds;
    }

    private function filterHiddenDropdowns($id): bool
    {
         $dropdown = $this->dropdownCollectionFactory->create()->getItemById($id);
        if ($dropdown->getData('hide_in_form')) {
            return true;
        } else {
            return false;
        }
    }
}
