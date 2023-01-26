<?php

declare(strict_types=1);

namespace Ecommerce121\HubSpot\Model\Store;

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
     * @return string|null
     */
    public function getScriptNumber(): ?string
    {
        $xmlConfigPath = $this->data['script_number'] ?? '';
        if (!$xmlConfigPath) {
            return null;
        }

        return (string) $this->scopeConfig->getValue($xmlConfigPath, ScopeInterface::SCOPE_STORE);
    }
}
