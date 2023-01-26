<?php

declare(strict_types=1);

namespace Ecommerce121\CubedPriceAttributes\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class CreateAttributePackageLength implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * Attribute name
     */
    const ATTRIBUTE_NAME = 'package_length';

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(Product::ENTITY, self::ATTRIBUTE_NAME,
        ['type' => 'varchar',
        'label' => 'Package Length',
        'input' => 'text',
        'user_defined' => true,
        'visible' => true,
        'frontend' => '',
        'backend' => '',
        'used_in_product_listing' => true,
        'is_used_in_grid' => true,
        'required' => false,
        'sort_order' => 1,
        'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
        'group' => 'Package Attributes']);
    }

    /**
     * @return array
     */
    public static function getDependencies()
    {
        return [

        ];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
