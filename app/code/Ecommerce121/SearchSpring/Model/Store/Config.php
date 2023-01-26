<?php

declare(strict_types=1);

namespace Ecommerce121\SearchSpring\Model\Store;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const CATALOGSEARCH_CONFIG_VALUE = 'catalogsearch';
    const CATALOGSEARCH_CONFIG_LABEL = 'Search results';

    const CATALOG_SEARCHSPRING_SITE_ID = 'catalog/searchspring/site_id';
    const CATALOG_SEARCHSPRING_ENABLED = 'catalog/searchspring/enabled';
    const CATALOG_SEARCHSPRING_OPTIONS = 'catalog/searchspring/options';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var string[]|null
     */
    private $data;

    /**
     * @var Registry
     */
    private Registry $registry;

    /**
     * @var UrlInterface
     */
    private UrlInterface $urlInterface;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Registry $registry
     * @param UrlInterface $urlInterface
     * @param string[]|null $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Registry $registry,
        UrlInterface $urlInterface,
        array $data = []
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->data = $data;
        $this->registry = $registry;
        $this->urlInterface = $urlInterface;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::CATALOG_SEARCHSPRING_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return string
     */
    public function getSiteId(): string
    {
        return (string) $this->scopeConfig->getValue(
            self::CATALOG_SEARCHSPRING_SITE_ID,
            ScopeInterface::SCOPE_STORE
        ) ?? '';
    }


    /**
     * @return array
     */
    public function getSelectedOptions(): array
    {
        $optionsIds = $this->scopeConfig->getValue(
            self::CATALOG_SEARCHSPRING_OPTIONS,
            ScopeInterface::SCOPE_STORE
        );

        if (!$optionsIds) {
            return [];
        }

        return explode(',', $optionsIds);
    }

    /**
     * @return bool
     */
    protected function isSearchApplicable(): bool
    {
        $selectedOptions = $this->getSelectedOptions();
        $currentUrl = $this->urlInterface->getCurrentUrl();

        if (stripos($currentUrl, self::CATALOGSEARCH_CONFIG_VALUE) !== false) {
            return (in_array(self::CATALOGSEARCH_CONFIG_VALUE, $selectedOptions));
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isSearchSpringAllowed(): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return $this->isSearchApplicable();
    }
}
