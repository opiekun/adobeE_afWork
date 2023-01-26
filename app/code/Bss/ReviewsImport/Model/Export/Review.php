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
 * @package    Bss_ReviewsImport
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\ReviewsImport\Model\Export;

/**
 * Class Review
 *
 * @package Bss\ReviewsImport\Model\Export
 */
class Review extends \Magento\ImportExport\Model\Export\AbstractEntity
{
    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Framework\Module\ModuleListInterface
     */
    protected $moduleList;

    /**
     * Review constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\ImportExport\Model\Export\Factory $collectionFactory
     * @param \Magento\ImportExport\Model\ResourceModel\CollectionByPagesIteratorFactory $resourceColFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\ImportExport\Model\Export\Factory $collectionFactory,
        \Magento\ImportExport\Model\ResourceModel\CollectionByPagesIteratorFactory $resourceColFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Module\ModuleListInterface $moduleList,
        array $data = []
    ) {
        parent::__construct($scopeConfig, $storeManager, $collectionFactory, $resourceColFactory, $data);
        $this->urlBuilder = $urlBuilder;
        $this->moduleList = $moduleList;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return [
            "type" => "redirect",
            "url" => $this->urlBuilder->getUrl("reviewsimport")
        ];
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        $moduleInfo = $this->moduleList->getOne("Bss_ReviewsImport");
        return $moduleInfo['setup_version'];
    }

    /**
     * Export process
     *
     * @inheritdoc
     */
    public function export()
    {
        // TODO: Implement exportItem() method.
        return $this;
    }

    /**
     * Export one item
     *
     * @inheritdoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function exportItem($item)
    {
        // TODO: Implement exportItem() method.
        return $this;
    }

    /**
     * Entity type code getter
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'bss_review';
    }

    /**
     * Get header columns
     *
     * @inheritdoc
     */
    protected function _getHeaderColumns()
    {
        // TODO: Implement _getHeaderColumns() method.
        return $this;
    }

    /**
     * Get entity collection
     *
     * @inheritdoc
     */
    protected function _getEntityCollection()
    {
        // TODO: Implement _getEntityCollection() method.
        return $this;
    }
}
