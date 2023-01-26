<?php

declare(strict_types=1);

namespace Ecommerce121\AmastyFinder\Plugin;

use Amasty\Finder\Block\Product\View\OptionsList;
use Ecommerce121\AmastyFinder\Model\FinderManager;
use Ecommerce121\AmastyFinder\Model\RangeManager;

class RangeFormatter
{
    private const SEPARATOR = '|^|^|^|^|';

    private $emptyKeys;

    /**
     * @var FinderManager
     */
    private $finderManager;

    /**
     * @var RangeManager
     */
    private $rangeManager;

    /**
     * @param FinderManager $finderManager
     * @param RangeManager $rangeManager
     */
    public function __construct(FinderManager $finderManager, RangeManager $rangeManager)
    {
        $this->finderManager = $finderManager;
        $this->rangeManager = $rangeManager;
    }

    /**
     * Filter out empty dropdown column values and apply range
     *
     * @param OptionsList $subject
     * @param array $result
     * @return array[]
     */
    public function afterGetFinderOptions(OptionsList $subject, array $result)
    {
        if (!isset($this->emptyKeys)) {
            foreach (array_keys(reset($result)) as $key) {
                $optionValues = array_column($result, $key);
                if (!array_filter($optionValues)) {
                    $this->emptyKeys[] = $key;
                }
            }
        }

        if (isset($this->emptyKeys)) {
            $result = $this->unsetEmptyColumns($result);
        }

        if ($this->finderManager->isRangeMode()) {
            return $this->applyRangesToTheFirstItem($result);
        }

        return $result;
    }

    /**
     * @param array $data
     * @return array[]
     */
    private function unsetEmptyColumns($data): array
    {
        foreach ($data as $id => $option) {
            for ($i = 0; $i < count($this->emptyKeys); $i++) {
                unset($data[$id][$this->emptyKeys[$i]]);
            }
        }
        return $data;
    }

    /**
     * @param array $items
     * @return array[]
     */
    private function applyRangesToTheFirstItem(array $items): array
    {
        $result = [];
        foreach ($this->getRangeCandidates($items) as $data => $numbersForRanging) {
            $ranges = $this->rangeManager->getRanges($numbersForRanging);

            foreach ($ranges as $range) {
                $originalItem = explode(self::SEPARATOR, $data);
                array_unshift($originalItem, $range);
                $result[] = $originalItem;
            }
        }
        // Sorting by Make --> Model ---> Year ---> Engine
        array_multisort(
            array_column($result, 1), SORT_ASC, // make
            array_column($result, 2), SORT_ASC, // model
            array_column($result, 0), SORT_ASC, // year
            array_column($result, 4), SORT_ASC, // engine
            $result
        );
        return $result;
    }

    /**
     * @param array $items
     * @return int[]
     */
    private function getRangeCandidates(array $items): array
    {
        $candidates = [];
        foreach ($items as $item) {
            $firstItem = array_shift($item);
            $candidates[implode(self::SEPARATOR, $item)][] = (int) $firstItem;
        }

        return $candidates;
    }

    /**
     * Filter out empty dropdown column names
     *
     * @param OptionsList $subject
     * @param array $result
     * @return array
     */
    public function afterGetDropdownNames(OptionsList $subject, array $result): array
    {
        if (isset($this->emptyKeys)) {
            foreach ($result as $option) {
                for ($i = 0; $i < count($this->emptyKeys); $i++) {
                    unset($result[$this->emptyKeys[$i]]);
                }
            }
        }
        return $result;
    }
}
