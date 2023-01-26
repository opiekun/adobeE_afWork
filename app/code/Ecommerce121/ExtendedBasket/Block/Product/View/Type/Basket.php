<?php

declare(strict_types=1);

namespace Ecommerce121\ExtendedBasket\Block\Product\View\Type;

use Ecommerce121\Basket\Block\Product\View\Type\Basket as BasketProductType;

class Basket extends BasketProductType
{
    /**
     * @inheritDoc
     */
    protected function _prepareLayout(): Basket
    {
        parent::_prepareLayout();
        $product = $this->_coreRegistry->registry('product');
        if (!$product->getBasketLayout() === 'basket_normal_view') {
            $this->setTemplate('Ecommerce121_ExtendedBasket::product/view/type/slider.phtml');
        }

        return $this;
    }
}
