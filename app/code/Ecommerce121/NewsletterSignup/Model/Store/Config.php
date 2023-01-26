<?php

declare(strict_types=1);

namespace Ecommerce121\NewsletterSignup\Model\Store;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Serialize\Serializer\Json;

class Config
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var string[]|null
     */
    private $data;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param Json $json
     * @param string[]|null $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Json $json,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->data = $data;
        $this->json = $json;
    }

    /**
     * @return string|null
     */
    public function getEndpoint(): ?string
    {
        $xmlConfigPath = $this->data['endpoint'] ?? '';
        if (!$xmlConfigPath) {
            return null;
        }

        return $this->scopeConfig->getValue($xmlConfigPath, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return array
     */
    public function getRequestParameterMapping(): array
    {
        $xmlConfigPath = $this->data['request_parameter_mapping'] ?? '';
        if (!$xmlConfigPath) {
            return [];
        }

        return $this->json->unserialize($this->scopeConfig->getValue($xmlConfigPath, ScopeInterface::SCOPE_STORE));
    }
}
