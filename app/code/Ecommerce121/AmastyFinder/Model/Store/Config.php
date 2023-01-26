<?php

declare(strict_types=1);

namespace Ecommerce121\AmastyFinder\Model\Store;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_PATH_AMASTY_FINDER_ENABLED = 'amfinder/export/enabled';
    const XML_PATH_AMASTY_FINDER_FINDER_ID = 'amfinder/export/finder_id';
    const XML_PATH_AMASTY_FINDER_PATH = 'amfinder/export/file_name';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_AMASTY_FINDER_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return int|null
     */
    public function getFinderId(): ?int
    {
        return (int) $this->scopeConfig->getValue(
            self::XML_PATH_AMASTY_FINDER_FINDER_ID,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string|null
     */
    public function getFilePathAndName(): ?string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_AMASTY_FINDER_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }
}
