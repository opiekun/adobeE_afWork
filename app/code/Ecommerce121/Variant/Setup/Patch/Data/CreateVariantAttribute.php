<?php

declare(strict_types=1);

namespace Ecommerce121\Variant\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Attribute\Frontend\Image as ImageFrontendModel;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Psr\Log\LoggerInterface;

class CreateVariantAttribute implements DataPatchInterface
{
    const ATTRIBUTE_CODE = 'variant';
    const ATTRIBUTE_LABEL = 'Variant Image';

    /**
     * ModuleDataSetupInterface
     *
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * EavSetupFactory
     *
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->logger = $logger;
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function apply(): CreateVariantAttribute
    {
        try {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

            $eavSetup->addAttribute(
                Product::ENTITY,
                self::ATTRIBUTE_CODE, [
                'type' => 'varchar',
                'label' => self::ATTRIBUTE_LABEL,
                'input' => 'media_image',
                'frontend' => ImageFrontendModel::class,
                'required' => false,
                'sort_order' => 40,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'used_in_product_listing' => true,
                'group' => 'General',
            ]);
        } catch (LocalizedException|\Zend_Validate_Exception $e) {
            $this->logger->critical(
                __('Error while creating variant image role attribute.')
            );
        }

        return $this;
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
