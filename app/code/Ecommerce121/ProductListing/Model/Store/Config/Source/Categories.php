<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\Model\Store\Config\Source;

use Amasty\MegaMenuLite\Model\ResourceModel\CategoryCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Store\Model\StoreManagerInterface;

class Categories implements OptionSourceInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheridoc
     */
    public function toOptionArray(): array
    {
        $store = $this->storeManager->getDefaultStoreView();
        $categoryCollection = $this->categoryCollectionFactory->create()
            ->getIncludedInMenuCategories($store->getId())
            ->addFieldToFilter('parent_id', ['eq' => $store->getRootCategoryId()]);

        $result = [];
        foreach ($categoryCollection->getItems() as $category) {
            $result[] = [
                'value' => $category->getId(),
                'label' => $category->getName(),
            ];
        }

        return $result;
    }
}
