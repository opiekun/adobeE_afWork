<?php

declare(strict_types=1);

namespace Ecommerce121\CatalogData\Setup\Patch\Data;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class UpdateTextAttributes implements DataPatchInterface
{
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
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param Csv $csv
     * @param Reader $dirReader
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup,
        Csv $csv,
        Reader $dirReader
    ) {
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
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $entityType = ProductAttributeInterface::ENTITY_TYPE_CODE;
        $attributes = $this->getAttributes();

        foreach ($attributes as $attributeCode) {
            $attributeId = $eavSetup->getAttribute($entityType, $attributeCode, 'attribute_id');

            if ($attributeId) {
                $eavSetup->updateAttribute($entityType, $attributeId, 'is_filterable', 0);
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
            . '/Patch/Fixtures/text_attributes.csv';
        $rows = $this->csv->getData($filePath);
        $result = [];
        foreach ($rows as $row) {
            [$attributeName, $attributeSets, $unitOfMeasure] = $row;

            if (!$attributeName) {
                continue;
            }

            $result[] = $this->getAttributeCode($attributeName);
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
        return [CreateTextAttributes::class];
    }
}
