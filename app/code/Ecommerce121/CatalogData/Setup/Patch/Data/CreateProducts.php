<?php

declare(strict_types=1);

namespace Ecommerce121\CatalogData\Setup\Patch\Data;

use Ecommerce121\CatalogData\Api\AttributeSetInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\File\Csv;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\App\State;

class CreateProducts implements DataPatchInterface
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
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ProductInterfaceFactory
     */
    private $productFactory;

    /**
     * @var Csv
     */
    private $csv;

    /**
     * @var Reader
     */
    private $dirReader;

    /**
     * @var State
     */
    private $state;

    /**
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param ProductRepositoryInterface $productRepository
     * @param ProductInterfaceFactory $productFactory
     * @param Csv $csv
     * @param Reader $dirReader
     * @param State $state
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup,
        ProductRepositoryInterface $productRepository,
        ProductInterfaceFactory $productFactory,
        Csv $csv,
        Reader $dirReader,
        State $state
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->productRepository = $productRepository;
        $this->productFactory = $productFactory;
        $this->csv = $csv;
        $this->dirReader = $dirReader;
        $this->state = $state;
    }

    /**
     * {@inheritDoc}
     * @throws \Exception
     */
    public function apply()
    {
        $this->state->setAreaCode('frontend');
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $attributeSet = $eavSetup->getAttributeSet(
            ProductAttributeInterface::ENTITY_TYPE_CODE,
            AttributeSetInterface::COLD_AIR_INTAKE
        );

        foreach ($this->getProductData() as $productData) {
            $product = $this->productFactory->create();
            $product->addData($productData);
            $product->setAttributeSetId($attributeSet['attribute_set_id']);
            $product->setName($productData['sku']);

            foreach ($productData as $attributeCode => $value) {
                $attr = $product->getResource()->getAttribute($attributeCode);
                if ($attr->usesSource()) {
                    $optionId = $attr->getSource()->getOptionId($value);
                    $product->setData($attributeCode, $optionId);
                }
            }

            $this->productRepository->save($product);
        }

        return $this;
    }

    /**
     * @return \Generator
     * @throws \Exception
     */
    private function getProductData(): \Generator
    {
        $filePath = $this->dirReader->getModuleDir('Setup', 'Ecommerce121_CatalogData')
            . '/Patch/Fixtures/products.csv';
        $this->csv->setDelimiter(';');
        $rows = $this->csv->getData($filePath);

        $keys = array_shift($rows);

        foreach ($rows as $row) {
            yield \array_combine($keys, $row);
        }
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
        return [
            CreateAttributeSets::class,
            CreateBooleanAttributes::class,
            CreateDropdownAttributes::class,
            CreateTextAttributes::class,
        ];
    }
}
