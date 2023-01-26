<?php

declare(strict_types=1);

namespace Ecommerce121\AttributeAssetsIcon\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Ecommerce121\AttributeAssetsIcon\Model\Store\Config;
use Magento\Store\Model\StoreManagerInterface;

class FilesConfiguration implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * FilesConfiguration constructor.
     * @param Config $config
     */
    public function __construct(Config $config, StoreManagerInterface $storeManager)
    {
        $this->config = $config;
        $this->storeManager = $storeManager;
    }

    /**
     * Get configuration for icons path images
     * @return Config
     */
    public function getConfiguration()
    {
        return $this->config;
    }

    /**
     * Get media url to icons
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrlToIcons()
    {
        $currentStore =  $this->storeManager->getStore();
        return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'assets_icon/';
    }

    /**
     * Get media url to files
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaUrlToFiles()
    {
        $currentStore =  $this->storeManager->getStore();
        return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/file/';
    }
}
