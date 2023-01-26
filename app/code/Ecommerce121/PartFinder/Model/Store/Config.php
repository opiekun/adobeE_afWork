<?php

declare(strict_types=1);

namespace Ecommerce121\PartFinder\Model\Store;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    private const XML_PATH_CATEGORY_EXCLUDED = 'ecommerce121_partfinder/general/exclude_category_id';
    private const XML_PATH_CATEGORIES_TO_DISPLAY = 'ecommerce121_partfinder/general/categories_to_display';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param string[]|null $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->data = $data;
    }

    /**
     * @return string|null
     */
    public function getExcludedCategories(): ?string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_CATEGORY_EXCLUDED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return array|null
     */
    public function getCategoriesToDisplay(): array
    {
        $selectedCategories = $this->scopeConfig->getValue(
            self::XML_PATH_CATEGORIES_TO_DISPLAY,
            ScopeInterface::SCOPE_STORE
        ) ?? [];

        return $selectedCategories ? explode(',', $selectedCategories) : [];
    }
}
