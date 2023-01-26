<?php

declare(strict_types=1);

namespace Ecommerce121\ProductPage\Block\Product\View;

use Amasty\ShopbyBase\Api\Data\OptionSettingRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Store\Model\ScopeInterface;

class BrandImage extends Template
{
    /**
     * @var OptionSettingRepositoryInterface
     */
    private $optionSettingRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param OptionSettingRepositoryInterface $optionSettingRepository
     * @param ProductRepositoryInterface $productRepository
     * @param RequestInterface $request
     * @param ScopeConfigInterface $scopeConfig
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        OptionSettingRepositoryInterface $optionSettingRepository,
        ProductRepositoryInterface $productRepository,
        RequestInterface $request,
        ScopeConfigInterface $scopeConfig,
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->optionSettingRepository = $optionSettingRepository;
        $this->productRepository = $productRepository;
        $this->request = $request;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string|null
     */
    public function getBrandImageUrl(): ?string
    {
        $productId = (int) $this->request->getParam('id');
        try {
            $product = $this->productRepository->getById($productId);
        } catch (LocalizedException $e) {
            return null;
        }

        $brandCode =  $this->getBrandCode();

        $option = $this->optionSettingRepository->getByParams(
            'attr_' . $brandCode,
            $product->getData($brandCode),
            $product->getStoreId()
        );

        return $option->getSliderImageUrl();
    }

    /**
     * @return string
     */
    private function getBrandCode(): string
    {
        return (string) $this->scopeConfig->getValue(
            'amshopby_brand/general/attribute_code',
            ScopeInterface::SCOPE_STORE
        );
    }
}
