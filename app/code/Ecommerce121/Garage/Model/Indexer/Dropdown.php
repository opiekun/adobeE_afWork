<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model\Indexer;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Indexer\AbstractProcessor;
use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;
use Magento\Framework\Indexer\IndexerRegistry;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;

class Dropdown extends AbstractProcessor implements IndexerActionInterface, MviewActionInterface
{
    const INDEXER_ID = 'garage_finder_dropdown';

    /**
     * @var IndexBuilder
     */
    private $indexBuilder;

    /**
     * @var IndexerRegistry
     */
    protected $indexerRegistry;

    /**
     * @param IndexerRegistry $indexerRegistry
     * @param IndexBuilder $indexBuilder
     */
    public function __construct(
        IndexerRegistry $indexerRegistry,
        IndexBuilder $indexBuilder
    ) {
        $this->indexerRegistry = $indexerRegistry;
        $this->indexBuilder = $indexBuilder;
    }

    /**
     * Execute materialization on ids entities
     * @param int[] $ids
     * @throws LocalizedException
     */
    public function execute($ids)
    {
        $this->executeList($ids);
    }

    /**
     * Execute full indexation
     */
    public function executeFull()
    {
        $this->indexBuilder->reindexAll();
    }

    /**
     * Execute partial indexation by ID list
     * @param array $ids
     * @throws LocalizedException
     */
    public function executeList(array $ids)
    {
        $this->doExecuteList($ids);
    }

    /**
     * Execute partial indexation by ID
     *
     * @param int $id
     * @throws LocalizedException
     * @return void
     */
    public function executeRow($id)
    {
        if ($this->getIndexer()->isScheduled()) {
            return;
        }
        if (!$id) {
            throw new LocalizedException(
                __('We can\'t rebuild the index for an undefined dropdown.')
            );
        }

        $this->doExecuteRow($id);
    }

    /**
     * Execute partial indexation by ID list. Template method
     * @param $ids
     * @throws LocalizedException
     */
    private function doExecuteList($ids)
    {
        $this->indexBuilder->reindexByDropdownId($ids);
    }

    /**
     * Execute partial indexation by ID. Template method
     *
     * @param int $id
     * @throws LocalizedException
     * @return void
     */
    private function doExecuteRow($id)
    {
        $this->indexBuilder->reindexByDropdownId($id);
    }

    /**
     * @throws LocalizedException
     */
    public function doExecuteFull()
    {
        $this->executeFull();
    }

    /**
     * Execute partial indexation by ID
     * @param  int $id
     */
    public function executeByDropdownId($id)
    {
        $this->indexBuilder->reindexByDropdownId($id);
    }

    /**
     * Execute partial indexation by ID
     * @param  int $id
     */
    public function executeByValueId($id)
    {
        $this->indexBuilder->reindexByValueId($id);
    }

    /**
     * Execute partial indexation by ID
     * @param  array $ids
     */
    public function executeByValueIds($ids)
    {
        $this->indexBuilder->reindexByValueIds($ids);
    }

    /**
     * Execute partial indexation by ID
     * @param  array $ids
     */
    public function executeByDropdownIds($ids)
    {
        $this->indexBuilder->reindexByDropdownIds($ids);
    }

    public function invalidateIndex()
    {
        $dropdownIndexer = $this->getIndexer();
        if (!$dropdownIndexer->isScheduled()) {
            $dropdownIndexer->invalidate();
        }
    }
}
