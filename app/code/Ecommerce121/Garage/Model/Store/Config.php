<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Model\Store;

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
    public function __construct(ScopeConfigInterface $scopeConfig, $data = [])
    {
        $this->scopeConfig = $scopeConfig;
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function getDefaultFinderId(): ?int
    {
        $xmlConfigPath = $this->data['default_finder_id'] ?? '';
        if (!$xmlConfigPath) {
            return null;
        }

        return (int) $this->scopeConfig->getValue($xmlConfigPath, ScopeInterface::SCOPE_STORE);
    }
}
