<?php

declare(strict_types=1);

namespace Ecommerce121\AttributeAssetsIcon\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class CreateAttributeDocument implements DataPatchInterface
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
    const ATTRIBUTE_NAME = 'asset_document';

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
        'label' => 'Document',
        'input' => 'file',
        'user_defined' => true,
        'visible' => true,
        'frontend' => '',
        'backend' => 'Ecommerce121\AttributeAssetsIcon\Model\Product\Attribute\Backend\File',
        'used_in_product_listing' => true,
        'required' => false,
        'sort_order' => 1,
        'global' => ScopedAttributeInterface::SCOPE_STORE,
        'group' => CreateAttributeGroup::ATTRIBUTE_GROUP_NAME]);
    }

    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [CreateAttributeGroup::class];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
