<?php

declare(strict_types=1);

namespace Ecommerce121\SalesPartialShipments\Model\Store\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     */
    public function isEnabled(): string
    {
        return (string) $this->scopeConfig->getValue('sales/partial_shipments/enabled', ScopeInterface::SCOPE_STORE);
    }
}