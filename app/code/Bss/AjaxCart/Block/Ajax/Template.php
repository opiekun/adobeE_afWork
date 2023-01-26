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
 * @category   BSS
 * @package    Bss_AjaxCart
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\AjaxCart\Block\Ajax;

use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Template
 *
 * @package Bss\AjaxCart\Block\Ajax
 */
class Template extends \Magento\Framework\View\Element\Template
{
    /**
     * Ajax cart helper.
     *
     * @var \Bss\AjaxCart\Helper\Data
     */
    protected $ajaxHelper;

    /**
     * Catalog image helper.
     *
     * @var \Magento\Catalog\Helper\Image
     */
    protected $imageHelper;

    /**
     * Checkout cart helper.
     *
     * @var \Magento\Checkout\Helper\Cart
     */
    protected $cartHelper;

    /**
     * Pricing helper.
     *
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $pricingHelper;

    /**
     * Weee helper.
     *
     * @var \Magento\Weee\Helper\Data
     */
    protected $weeeHelper;

    /**
     * @var Json
     */
    protected $jsonEncoder;

    /**
     * Initialize dependencies.
     *
     * @param Json $jsonEncoder
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Bss\AjaxCart\Helper\Data $ajaxHelper
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Checkout\Helper\Cart $cartHelper
     * @param \Magento\Framework\Pricing\Helper\Data $pricingHelper
     * @param \Magento\Weee\Helper\Data $weeeHelper
     */
    public function __construct(
        Json $jsonEncoder,
        \Magento\Framework\View\Element\Template\Context $context,
        \Bss\AjaxCart\Helper\Data $ajaxHelper,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Checkout\Helper\Cart $cartHelper,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Weee\Helper\Data $weeeHelper
    ) {
        $this->jsonEncoder = $jsonEncoder;
        $this->ajaxHelper = $ajaxHelper;
        $this->imageHelper = $imageHelper;
        $this->cartHelper = $cartHelper;
        $this->pricingHelper = $pricingHelper;
        $this->weeeHelper = $weeeHelper;
        parent::__construct($context, []);
    }

    /**
     * Get ajax cart helper.
     *
     * @return \Bss\AjaxCart\Helper\Data
     */
    public function getAjaxHelper()
    {
        return $this->ajaxHelper;
    }

    /**
     * Get catalog image helper.
     *
     * @return \Magento\Catalog\Helper\Image
     */
    public function getImageHelper()
    {
        return $this->imageHelper;
    }

    /**
     * Get checkout cart helper.
     *
     * @return \Magento\Checkout\Helper\Cart
     */
    public function getCartHelper()
    {
        return $this->cartHelper;
    }

    /**
     * Get pricing helper.
     *
     * @return \Magento\Framework\Pricing\Helper\Data
     */
    public function getPricingHelper()
    {
        return $this->pricingHelper;
    }

    /**
     * Get weee helper
     *
     * @return \Magento\Weee\Helper\Data
     */
    public function getWeeHelper()
    {
        return $this->weeeHelper;
    }

    /**
     * Get config Fly to cart
     *
     * @return string
     */
    public function getConfigFlyToCart()
    {
        $transparent = 1;
        if ($this->ajaxHelper->getTransparent() == 1) {
            $transparent = 0.5;
        }
        $data = [];
        $data['enable'] = $this->ajaxHelper->getEnableFlyToCart();
        $data['speed'] = $this->ajaxHelper->getFlyingSpeed();
        $data['transparent'] = $transparent;
        return $this->jsonEncoder->serialize($data);
    }

    /**
     * Get Config Popup Animation
     *
     * @return mixed
     */
    public function getConfigAnimation()
    {
        return $this->ajaxHelper->getConfigAnimation();
    }
}
