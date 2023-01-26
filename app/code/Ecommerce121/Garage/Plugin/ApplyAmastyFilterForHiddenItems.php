<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Plugin;

use Amasty\Finder\Model\Session as FinderSession;
use Amasty\Finder\Model\Finder;

class ApplyAmastyFilterForHiddenItems
{
    /**
     * @var FinderSession
     */
    private $finderSession;

    /**
     * @param FinderSession $finderSession
     */
    public function __construct(FinderSession $finderSession)
    {
        $this->finderSession = $finderSession;
    }

    /**
     * @param Finder $subject
     * @param int|bool $result
     * @param string|int $dropdownId
     * @return int|bool
     */
    public function afterGetSavedValue(Finder $subject, $result, $dropdownId)
    {
        if ($result) {
            return $result;
        }

        $values = $this->finderSession->getFinderData($subject->getId());
        if (is_array($values) && isset($values[$dropdownId]) && $values[$dropdownId]) {
            return $values[$dropdownId];
        }

        return $result;
    }
}
