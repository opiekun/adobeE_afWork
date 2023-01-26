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
namespace Bss\AjaxCart\Block\Popup;

class Suggest extends \Magento\Catalog\Block\Product\AbstractProduct
{
    /**
     * Ajax cart helper.
     *
     * @var \Bss\AjaxCart\Helper\Data
     */
    protected $helper;

    /**
     * Catalog product model.
     *
     * @var \Magento\Catalog\Model\Product
     */
    protected $product;

    /**
     * Post data helper.
     *
     * @var \Magento\Framework\Data\Helper\PostHelper
     */
    protected $postDataHelper;

    /**
     * Catalog product visibility helper.
     *
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $catalogProductVisibility;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Catalog\Block\Product\Context $context
     * @param \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param \Bss\AjaxCart\Helper\Data $helper
     * @param \Magento\Catalog\Model\Product $product
     * @param \Magento\Framework\Data\Helper\PostHelper $postDataHelper
     * @param array $data
     */
    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Bss\AjaxCart\Helper\Data $helper,
        \Magento\Catalog\Model\Product $product,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->helper = $helper;
        $this->product = $product;
        $this->postDataHelper = $postDataHelper;
    }

    /**
     * Get ajax cart helper.
     *
     * @return \Bss\AjaxCart\Helper\Data
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * Get post data helper.
     *
     * @return \Magento\Framework\Data\Helper\PostHelper
     */
    public function getPostDataHelper()
    {
        return $this->postDataHelper;
    }

    /**
     * Get suggested product collection.
     *
     * @return \Magento\Catalog\Model\ResourceModel\Product\Link\Product\Collection
     */
    public function getProductCollection()
    {
        $helper = $this->getHelper();
        $product = $this->getProduct();
        $source = $helper->getSuggestSource();

        switch ($source) {
            case \Bss\AjaxCart\Model\Config\Source\Suggest::SUGGEST_SOURCE_RELATED:
                $collection = $product->getRelatedProductCollection();
                break;
            case \Bss\AjaxCart\Model\Config\Source\Suggest::SUGGEST_SOURCE_UPSELL:
                $collection = $product->getUpSellProductCollection();
                break;
            case \Bss\AjaxCart\Model\Config\Source\Suggest::SUGGEST_SOURCE_XSELL:
                $collection = $product->getCrossSellProductCollection();
                break;
            default:
                $collection = $product->getRelatedProductCollection();
        }

        $collection->addAttributeToSelect('required_options')->setPositionOrder()->addStoreFilter();
        $this->_addProductAttributesAndPrices($collection);
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());

        $limit = $helper->getSuggestLimit();

        if ($limit && $limit > 0) {
            $collection->setPageSize($limit);
        }

        $collection->load();

        return $collection;
    }

    /**
     * Get suggested block title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->getHelper()->getSuggestTitle();
    }

    /**
     * Get added product.
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if (!$this->product->getId()) {
            $productId = $this->getProductId();
            $this->product->load($productId);
        }

        return $this->product;
    }
}
