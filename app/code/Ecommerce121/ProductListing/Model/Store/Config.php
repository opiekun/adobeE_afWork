<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\Model\Store;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var string[]|null
     */
    private $data;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param string[]|null $data
     */
    public function __construct(ScopeConfigInterface $scopeConfig, array $data = [])
    {
        $this->scopeConfig = $scopeConfig;
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        $xmlConfigPath = $this->data['enabled'] ?? '';
        if (!$xmlConfigPath) {
            return false;
        }

        return $this->scopeConfig->isSetFlag($xmlConfigPath, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return int
     */
    public function getDefaultProductCategoryId(): ?int
    {
        $xmlConfigPath = $this->data['product_category'] ?? '';
        if (!$xmlConfigPath) {
            return null;
        }

        return (int) $this->scopeConfig->getValue($xmlConfigPath, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getProductModeCategoryIds(): array
    {
        $xmlConfigPath = $this->data['product_mode'] ?? '';
        if (!$xmlConfigPath) {
            return [];
        }

        $concatenatedIds = $this->scopeConfig->getValue($xmlConfigPath, ScopeInterface::SCOPE_STORE);
        if (!$concatenatedIds) {
            return [];
        }

        return explode(',', $concatenatedIds);
    }

    /**
     * @return string
     */
    public function getGenerationFilterLabel(): string
    {
        $xmlConfigPath = $this->data['generation_label'] ?? '';
        if (!$xmlConfigPath) {
            return '';
        }

        return (string) $this->scopeConfig->getValue($xmlConfigPath, ScopeInterface::SCOPE_STORE);
    }
}
