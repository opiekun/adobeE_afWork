<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Setup\CategorySetup;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Eav\Api\Data\AttributeFrontendLabelInterfaceFactory;
use Zend_Validate_Exception;

class CreateRankAttribute implements DataPatchInterface
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
     * @var ProductAttributeRepositoryInterface
     */
    private $productAttributeRepository;

    /**
     * @var AttributeFrontendLabelInterfaceFactory
     */
    private $attributeFrontendLabelFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param ProductAttributeRepositoryInterface $productAttributeRepository
     * @param AttributeFrontendLabelInterfaceFactory $attributeFrontendLabelFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        ProductAttributeRepositoryInterface $productAttributeRepository,
        AttributeFrontendLabelInterfaceFactory $attributeFrontendLabelFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->productAttributeRepository = $productAttributeRepository;
        $this->attributeFrontendLabelFactory = $attributeFrontendLabelFactory;
    }

    /**
     * @return CreateRankAttribute
     */
    public function apply(): CreateRankAttribute
    {
        /** @var CategorySetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $attribute = $eavSetup->getAttribute(Product::ENTITY, 'afe_product_ranking');
        if ($attribute) {
            $eavSetup->removeAttribute(Product::ENTITY, 'afe_product_ranking');
        }

        $this->createAttribute($eavSetup);

        return $this;
    }

    /**
     * @param EavSetup $eavSetup
     * @throws InputException
     * @throws LocalizedException
     * @throws NoSuchEntityException
     * @throws StateException
     * @throws Zend_Validate_Exception
     */
    private function createAttribute(EavSetup $eavSetup): void
    {
        $setId = $eavSetup->getDefaultAttributeSetId(Product::ENTITY);
        $groupId = $eavSetup->getDefaultAttributeGroupId(Product::ENTITY, $setId);
        $groupName = $eavSetup->getAttributeGroup(Product::ENTITY, $groupId, 'attribute_group_name');

        $eavSetup->addAttribute(Product::ENTITY, 'afe_product_ranking', [
                'filterable' => 1,
                'group' => $groupName,
                'input' => 'text',
                'label' => 'Product Ranking',
                'type' => 'int',
                'used_in_product_listing' => 1,
                'user_defined' => 1,
                'used_for_sort_by' => 1,
            ]
        );

        $attribute =$this->productAttributeRepository->get('afe_product_ranking');
        $attribute->setFrontendLabels([
            $this->attributeFrontendLabelFactory->create()
                ->setStoreId(1)
                ->setLabel('Most Popular'),
        ]);
        $this->productAttributeRepository->save($attribute);
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
