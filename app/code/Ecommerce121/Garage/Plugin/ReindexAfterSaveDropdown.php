<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Plugin;

use Amasty\Finder\Model\Dropdown;
use Amasty\Finder\Model\Finder;

class ReindexAfterSaveDropdown
{
    /**
     * @var Dropdown
     */
    private $dropdownIndexer;

    /**
     * @param \Ecommerce121\Garage\Model\Indexer\Dropdown $dropdownIndexer
     */
    public function __construct(
        \Ecommerce121\Garage\Model\Indexer\Dropdown $dropdownIndexer
    ) {
        $this->dropdownIndexer = $dropdownIndexer;
    }

    /**
     * @param Finder $subject
     * @param int|bool $result
     * @param string|int $dropdownId
     * @return int|bool
     */
    public function afterSave(Dropdown $subject, $result)
    {
        if ($result->getOrigData('hide_in_form') !== $result->getData('hide_in_form')) {
            if ($this->dropdownIndexer->isIndexerScheduled()) {
                $this->dropdownIndexer->invalidateIndex();
            } else {
                $this->dropdownIndexer->executeByDropdownId($result->getDropdownId());
            }
            return $result;
        }

        return $result;
    }
}
