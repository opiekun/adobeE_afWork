<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Block\Header;

use Amasty\Finder\Block\Form;
use Ecommerce121\Garage\Model\Store\Config;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class Finder extends Template
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
    }

    /**
     * It is here because of amasty magic in constructor: @see Form::apply();
     * Without this initialization the amasty search will not work
     *
     * @inheridoc
     */
    protected function _toHtml(): string
    {
         $this->getLayout()->createBlock(
             Form::class,
             'search',
             ['data' => ['id' => $this->config->getDefaultFinderId()]]
         );

        return parent::_toHtml();
    }
}
