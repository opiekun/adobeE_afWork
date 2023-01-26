<?php

declare(strict_types=1);

namespace Ecommerce121\AttributeAssetsIcon\Model\Store;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * Path to configuration
     */
    private const XML_ICON_FLOW_CHART = 'ecommerce121_attribute_assets_icon/general/icon_air_flow_chart';

    /**
     * Path to configuration
     */
    private const XML_ICON_INSTRUCTIONS = 'ecommerce121_attribute_assets_icon/general/icon_instructions';

    /**
     * Path to configuration
     */
    private const XML_ICON_DOCUMENT = 'ecommerce121_attribute_assets_icon/general/icon_document';

    /**
     * Path to configuration
     */
    private const XML_ICON_DYNO_CHART = 'ecommerce121_attribute_assets_icon/general/icon_dyno_chart';


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
    public function getIconFlowChart(): ? string
    {
        return $this->scopeConfig->getValue(self::XML_ICON_FLOW_CHART, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getIconInstructions(): ? string
    {
        return $this->scopeConfig->getValue(self::XML_ICON_INSTRUCTIONS, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getIconDocument(): ? string
    {
        return $this->scopeConfig->getValue(self::XML_ICON_DOCUMENT, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getIconDynoChart(): ? string
    {
        return $this->scopeConfig->getValue(self::XML_ICON_DYNO_CHART, ScopeInterface::SCOPE_STORE);
    }

}
