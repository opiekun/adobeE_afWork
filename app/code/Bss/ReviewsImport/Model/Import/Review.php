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
namespace Bss\ReviewsImport\Model\Import;

/**
 * Class Review
 *
 * @package Bss\ReviewsImport\Model\Import
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Review extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
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
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\Module\ModuleListInterface $moduleList
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Module\ModuleListInterface $moduleList
    ) {
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
     * Import data rows.
     *
     * @inheritdoc
     */
    protected function _importData()
    {
        // TODO: Implement _importData() method.
        return $this;
    }

    /**
     * EAV entity type code getter.
     *
     * @inheritdoc
     */
    public function getEntityTypeCode()
    {
        // TODO: Implement getEntityTypeCode() method.
        return $this;
    }

    /**
     * Validate data row.
     *
     * @param array $rowData
     * @param int $rowNum
     * @inheritdoc
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validateRow(array $rowData, $rowNum)
    {
        // TODO: Implement validateRow() method.
        return $this;
    }
}
