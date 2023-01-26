<?php

declare(strict_types=1);

namespace Ecommerce121\AmastyFinder\Model;

use Amasty\Finder\Api\FinderRepositoryInterface;
use Amasty\Finder\Helper\Config;
use Amasty\Finder\Model\Session;

class FinderManager
{
    /**
     * @var Config
     */
    private $configHelper;

    /**
     * @var FinderRepositoryInterface
     */
    private $finderRepository;

    /**
     * @var Session
     */
    private $session;

    /**
     * @param FinderRepositoryInterface $finderRepository
     * @param Session $session
     * @param Config $config
     */
    public function __construct(
        FinderRepositoryInterface $finderRepository,
        Session $session,
        Config $config
    ) {
        $this->finderRepository = $finderRepository;
        $this->session = $session;
        $this->configHelper = $config;
    }

    /**
     * @return bool
     */
    public function isRangeMode(): bool
    {
        $finderId = $this->getFinderId();
        $finder = $this->finderRepository->getById($finderId);

        return (bool) $finder->getData('range_by_first_dropdown');
    }

    /**
     * @return int
     */
    private function getFinderId()
    {
        $finderId = $this->configHelper->getConfigValue('advanced/compatible_finder');

        if (!$finderId) {
            $activeFinders = $this->session->getAllFindersData();
            if ($activeFinders) {
                $finderIds = array_keys($activeFinders);
                $finderId = array_shift($finderIds);
            }
        }

        return (int)$finderId;
    }
}
