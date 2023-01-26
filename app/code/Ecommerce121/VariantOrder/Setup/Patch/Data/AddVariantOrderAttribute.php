<?php declare(strict_types=1);

namespace Ecommerce121\VariantOrder\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Zend_Validate_Exception;

class AddVariantOrderAttribute implements DataPatchInterface
{

    const VARIANT_ORDER_CODE = 'variant_order';
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

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

    /**
     * @return AddVariantOrderAttribute
     * @throws LocalizedException
     * @throws Zend_Validate_Exception
     */
    public function apply(): AddVariantOrderAttribute
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        // Adds attribute for products
        $eavSetup->addAttribute(Product::ENTITY, self::VARIANT_ORDER_CODE, [
            'type' => 'varchar',
            'label' => 'Variant Order',
            'input' => 'text',
            'used_in_product_listing' => false,
            'user_defined' => true,
            'required' => false,
            'visible' => true,
            'apply_to' => 'simple'
        ]);
        $entityTypeId = $eavSetup->getEntityTypeId(Product::ENTITY);
        // Adds attribute to groups named 'Ecommerce121 Product Variants'
        $attributeSetId = $eavSetup->getAttributeSetId($entityTypeId, 'Default');
        $attributeGroupName = 'Ecommerce121 Product Variants';
        $attribute = $eavSetup->getAttribute($entityTypeId, self::VARIANT_ORDER_CODE);
        $eavSetup->addAttributeToGroup(
            $entityTypeId,
            $attributeSetId,
            $attributeGroupName,
            $attribute['attribute_id'],
            20
        );

        return $this;
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
