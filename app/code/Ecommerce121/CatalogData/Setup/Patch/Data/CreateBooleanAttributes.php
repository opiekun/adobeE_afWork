<?php

declare(strict_types=1);

namespace Ecommerce121\CatalogData\Setup\Patch\Data;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateBooleanAttributes implements DataPatchInterface
{
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
     * @var Csv
     */
    private $csv;

    /**
     * @var Reader
     */
    private $dirReader;

    /**
     * @param AttributeSetFactory $attributeSetFactory
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Csv $csv
     * @param Reader $dirReader
     */
    public function __construct(
        AttributeSetFactory $attributeSetFactory,
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup,
        Csv $csv,
        Reader $dirReader
    ) {
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->csv = $csv;
        $this->dirReader = $dirReader;
    }

    /**
     * {@inheritDoc}
     * @throws LocalizedException
     * @throws \Zend_Validate_Exception
     */
    public function apply()
    {
        $this->csv->setDelimiter(';');
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $entityType = ProductAttributeInterface::ENTITY_TYPE_CODE;
        $attributes = $this->getAttributes();

        foreach ($attributes as $attribute) {
            $attributeCode = $this->getAttributeCode($attribute['label']);
            $eavSetup->addAttribute(
                $entityType,
                $attributeCode,
                $this->getArguments($attribute['label'])
            );
            $attr = $eavSetup->getAttribute($entityType, $attributeCode);

            foreach ($attribute['sets'] as $attrSet) {
                $attributeSet = $eavSetup->getAttributeSet($entityType, $attrSet);
                $groupId = $eavSetup->getDefaultAttributeGroupId($entityType, $attributeSet['attribute_set_id']);

                $eavSetup->addAttributeToSet(
                    $entityType,
                    $attributeSet['attribute_set_id'],
                    $groupId,
                    $attr['attribute_id']
                );
            }
        }

        return $this;
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getAttributes(): array
    {
        $filePath = $this->dirReader->getModuleDir('Setup', 'Ecommerce121_CatalogData')
            . '/Patch/Fixtures/boolean_attributes.csv';
        $rows = $this->csv->getData($filePath);
        $result = [];
        foreach ($rows as $row) {
            [$attributeName, $attributeSets] = $row;

            if (!$attributeName) {
                continue;
            }

            $result[] = [
                'label' => $attributeName,
                'sets' => explode(',', $attributeSets),
            ];
        }

        return $result;
    }

    /**
     * @param string $name
     * @return string
     */
    private function getAttributeCode(string $name): string
    {
        $name = str_replace([' - ', '#'], '', trim($name));
        return strtolower(str_replace(' ', '_', trim($name)));
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
        return [CreateAttributeSets::class];
    }

    /**
     * @param string $attributeName
     * @return array
     */
    private function getArguments(string $attributeName): array
    {
        return [
            'comparable' => 1,
            'filterable' => 1,
            'input' => 'boolean',
            'label' => $attributeName,
            'frontend_input' => 'boolean',
            'source' => \Magento\Eav\Model\Entity\Attribute\Source\Boolean::class,
            'required' => 0,
            'searchable' => 1,
            'type' => 'int',
            'used_in_product_listing' => 1,
            'user_defined' => 1,
            'visible_in_advanced_search' => 1,
        ];
    }
}
