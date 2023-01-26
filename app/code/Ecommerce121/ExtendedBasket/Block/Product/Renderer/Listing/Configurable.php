<?php

declare(strict_types=1);

namespace Ecommerce121\ExtendedBasket\Block\Product\Renderer\Listing;

use Magento\Swatches\Block\Product\Renderer\Listing\Configurable as ConfigurableProductListingRenderer;
use Magento\Swatches\Block\Product\Renderer\Configurable as ConfigurableProductRenderer;

class Configurable extends ConfigurableProductListingRenderer
{
    /**
     * @inheritDoc
     */
    protected function _toHtml(): string
    {
        return ConfigurableProductRenderer::_toHtml();
    }
}
