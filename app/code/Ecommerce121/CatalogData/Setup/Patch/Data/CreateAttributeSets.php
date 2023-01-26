<?php

declare(strict_types=1);

namespace Ecommerce121\CatalogData\Setup\Patch\Data;

use Ecommerce121\CatalogData\Api\AttributeSetInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateAttributeSets implements DataPatchInterface
{
    /**
     * @var array
     */
    private $attributeSets = [
        AttributeSetInterface::AGD,
        AttributeSetInterface::BRAKE_CONTROLLER,
        AttributeSetInterface::COLD_AIR_INTAKE,
        AttributeSetInterface::DYNAMIC_AIR_SCOOPS,
        AttributeSetInterface::INTAKE_COVERS,
        AttributeSetInterface::PRE_FILTER,
        AttributeSetInterface::REPLACEMENT_INTAKE_AIR_FILTER,
        AttributeSetInterface::SPRINT_BOOSTER,
        AttributeSetInterface::TUBE_UPGRADE,
        AttributeSetInterface::TUNER_MODULE,
        AttributeSetInterface::TURBO_INLET,
    ];

    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param AttributeSetFactory $attributeSetFactory
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        AttributeSetFactory $attributeSetFactory,
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $entityType = ProductAttributeInterface::ENTITY_TYPE_CODE;
        $entityTypeId = $eavSetup->getEntityTypeId($entityType);
        $attributeSetId = $eavSetup->getDefaultAttributeSetId($entityTypeId);

        foreach ($this->attributeSets as $attributeSetName) {
            $attributeSet = $this->attributeSetFactory->create();
            $attributeSet->setData([
                'attribute_set_name' => $attributeSetName,
                'entity_type_id' => $entityTypeId,
            ]);
            $attributeSet->validate();

            $attributeSet->save(); // @codingStandardsIgnoreLine
            $attributeSet->initFromSkeleton($attributeSetId)->save(); // @codingStandardsIgnoreLine
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }
}
