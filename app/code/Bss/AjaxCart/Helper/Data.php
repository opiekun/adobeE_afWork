<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * BSS Commerce does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BSS Commerce does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   BSS
 * @package    Bss_AjaxCart
 * @author     Extension Team
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\AjaxCart\Helper;

/**
 * Class Data
 *
 * @package Bss\AjaxCart\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Is ajax cart enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(
            'ajaxcart/general/active',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is ajax cart enabled in product view.
     *
     * @return bool
     */
    public function isEnabledProductView()
    {
        return $this->scopeConfig->isSetFlag(
            'ajaxcart/general/active_product_view',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get add to cart button selector.
     *
     * @return string
     */
    public function getAddToCartSelector()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/general/selector',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show product image in success popup.
     *
     * @return bool
     */
    public function isShowProductImage()
    {
        return $this->scopeConfig->isSetFlag(
            'ajaxcart/success_popup/product_image',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get product image size in success popup.
     *
     * @return string
     */
    public function getImageSize()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/success_popup/product_image_size',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show added product price in success popup.
     *
     * @return bool
     */
    public function isShowProductPrice()
    {
        return $this->scopeConfig->isSetFlag(
            'ajaxcart/success_popup/product_price',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show continue button in success popup.
     *
     * @return bool
     */
    public function isShowContinueBtn()
    {
        return $this->scopeConfig->isSetFlag(
            'ajaxcart/success_popup/continue_button',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get countdown active for which button.
     *
     * @return string
     */
    public function getCountDownActive()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/success_popup/active_countdown',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get countdown time in second.
     *
     * @return string
     */
    public function getCountDownTime()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/success_popup/countdown_time',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show cart info in success popup.
     *
     * @return bool
     */
    public function isShowCartInfo()
    {
        return $this->scopeConfig->isSetFlag(
            'ajaxcart/success_popup/mini_cart',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show checkout link in success popup.
     *
     * @return bool
     */
    public function isShowCheckoutLink()
    {
        return $this->scopeConfig->isSetFlag(
            'ajaxcart/success_popup/mini_checkout',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show suggested products.
     *
     * @return bool
     */
    public function isShowSuggestBlock()
    {
        return $this->scopeConfig->isSetFlag(
            'ajaxcart/success_popup/suggest_product',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get suggest title.
     *
     * @return string
     */
    public function getSuggestTitle()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/success_popup/suggest_title',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get suggested source.
     *
     * @return int
     */
    public function getSuggestSource()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/success_popup/suggest_source',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get suggested limit.
     *
     * @return int
     */
    public function getSuggestLimit()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/success_popup/suggest_limit',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get button text color.
     *
     * @return mixed|string
     */
    public function getBtnTextColor()
    {
        $color = $this->scopeConfig->getValue(
            'ajaxcart/success_popup_design/button_text_color',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $color = ($color == '') ? 'ffffff' : $color;
        return $color;
    }

    /**
     * Get continue button text.
     *
     * @return string
     */
    public function getBtnContinueText()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/success_popup_design/continue_text',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get continue button background.
     *
     * @return mixed|string
     */
    public function getBtnContinueBackground()
    {
        $backGround = $this->scopeConfig->getValue(
            'ajaxcart/success_popup_design/continue',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $backGround = ($backGround == '') ? '1979c3' : $backGround;
        return $backGround;
    }

    /**
     * Get continue button color when hover.
     *
     * @return mixed|string
     */
    public function getBtnContinueHover()
    {
        $hover = $this->scopeConfig->getValue(
            'ajaxcart/success_popup_design/continue_hover',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $hover = ($hover == '') ? '006bb4' : $hover;
        return $hover;
    }

    /**
     * Get view cart button text.
     *
     * @return string
     */
    public function getBtnViewcartText()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/success_popup_design/viewcart_text',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get view cart button background.
     *
     * @return mixed|string
     */
    public function getBtnViewcartBackground()
    {
        $backGround = $this->scopeConfig->getValue(
            'ajaxcart/success_popup_design/viewcart',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $backGround = ($backGround == '') ? '1979c3' : $backGround;
        return $backGround;
    }

    /**
     * Get view cart button color when hover.
     *
     * @return mixed|string
     */
    public function getBtnViewcartHover()
    {
        $hover = $this->scopeConfig->getValue(
            'ajaxcart/success_popup_design/viewcart_hover',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        $hover = ($hover == '') ? '006bb4' : $hover;
        return $hover;
    }

    /**
     * Is show go to product link in quick view.
     *
     * @return bool
     */
    public function isShowQuickviewGotoLink()
    {
        return $this->scopeConfig->isSetFlag(
            'ajaxcart/quickview_popup/go_to_product',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Is show additional data in quick view.
     *
     * @return bool
     */
    public function isShowQuickviewAddData()
    {
        return $this->scopeConfig->isSetFlag(
            'ajaxcart/quickview_popup/additional_data',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get display product prices type in catalog.
     *
     * @return string
     */
    public function getProductTaxDisplayType()
    {
        return $this->scopeConfig->getValue(
            'tax/display/type',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get cart subtotal display price.
     *
     * @return string
     */
    public function getSubtotalDisplayType()
    {
        return $this->scopeConfig->getValue(
            'tax/cart_display/subtotal',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get status fly to cart
     *
     * @return mixed
     */
    public function getEnableFlyToCart()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/fly_to_cart/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get fly to cart speed
     *
     * @return mixed
     */
    public function getFlyingSpeed()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/fly_to_cart/cart_flying_animation_speed',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get transparent
     *
     * @return mixed
     */
    public function getTransparent()
    {
        return $this->scopeConfig->getValue(
            'ajaxcart/fly_to_cart/transparent_image_while_flying',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Popup Animation
     *
     * @return mixed
     */
    public function getConfigAnimation()
    {
        $getConfig = $this->scopeConfig->getValue(
            'ajaxcart/general/popup_animation',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        switch ($getConfig) {
            case 1:
                $animation = 'mfp-zoom-out';
                break;
            case 2:
                $animation = 'mfp-3d-unfold';
                break;
            case 3:
                $animation = 'mfp-move-from-top';
                break;
            case 4:
                $animation = 'mfp-move-horizontal';
                break;
            case 5:
                $animation = 'mfp-zoom-in';
                break;
            default:
                $animation = 'none';
        }
        return $animation;
    }
}
