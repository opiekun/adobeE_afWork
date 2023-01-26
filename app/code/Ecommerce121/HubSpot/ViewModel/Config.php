<?php

declare(strict_types=1);

namespace Ecommerce121\HubSpot\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ecommerce121\HubSpot\Model\Store\Config as ConfigModel;

class Config implements ArgumentInterface
{
    /**
     * ConfigModel
     */
    private $config;

    /**
     * Config construct.
     *
     * @param ConfigModel $config
     */
    public function __construct(ConfigModel $config)
    {
        $this->config = $config;
    }

    /**
     * Get script.
     */
    public function getScript(): ?string
    {
        return $this->config->getScriptNumber();
    }
}
