<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Setup\CategorySetup;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Catalog\Model\Category;

class AddCategoryAttributeForGenerations implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function apply()
    {
        /** @var CategorySetup $eavSetup */
        $eavSetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute(
            Category::ENTITY,
            'has_generation_filter',
            [
                'type' => 'int',
                'label' => 'Has Generation Filter',
                'input' => 'select',
                'used_in_product_listing' => true,
                'visible_on_front' => true,
                'source' => Boolean::class,
                'default' => '0',
                'sort_order' => 15,
                'user_defined' => 1,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General Information',
                'note' => 'The first level of children categories (if any) will be treated as Generation filter '
                    . 'on the product listing page'
            ]
        );
    }

    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
