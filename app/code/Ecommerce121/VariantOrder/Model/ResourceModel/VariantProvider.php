<?php declare(strict_types=1);

namespace Ecommerce121\VariantOrder\Model\ResourceModel;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

class VariantProvider extends \Ecommerce121\ProductVariation\Model\ResourceModel\VariantProvider
{
    private const VARIANT_KEY = 'variant_key';
    private const VARIANT_ORDER = 'variant_order';

    /**
     * @var CollectionFactory
     */
    private CollectionFactory $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }
    public function getProductsVariations(string $variantKey, string $excludeSku): array
    {
        if (!$variantKey) {
            return [];
        }

        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('*');
        $collection->addAttributeToFilter(self::VARIANT_KEY, $variantKey);
        $collection->addAttributeToSort(self::VARIANT_ORDER);
        $collection->addAttributeToFilter('sku', ['neq' => $excludeSku]);
        $collection->joinField(
            'stock_item',
            'cataloginventory_stock_item',
            'qty',
            'product_id=entity_id'
        );

        return $collection->getItems();
    }

}
