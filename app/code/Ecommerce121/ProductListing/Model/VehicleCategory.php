<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\Model;

use Ecommerce121\ProductListing\Model\ResourceModel\Catalog;
use Magento\Catalog\Api\Data\CategoryInterface;

class VehicleCategory
{
    /**
     * @var CategoryDataProvider
     */
    private $categoryDataProvider;

    /**
     * @var Catalog
     */
    private $catalog;

    /**
     * @param CategoryDataProvider $categoryDataProvider
     * @param Catalog $catalog
     */
    public function __construct(
        CategoryDataProvider $categoryDataProvider,
        Catalog $catalog
    ) {
        $this->categoryDataProvider = $categoryDataProvider;
        $this->catalog = $catalog;
    }

    /**
     * @param array $categories
     * @return array
     */
    public function filter(array $categories): array
    {
        $category = $this->categoryDataProvider->getCurrentCategory();
        $productIds = $category->getProductCollection()->getAllIds();

        $allCategoryIds = array_unique($this->catalog->getCategoryIdsByProductIds($productIds));
        $filtered = [];
        foreach ($categories as $category) {
            if ($this->isAllowed($category, $allCategoryIds)) {
                $filtered[] = $category;
            }
        }

        return $filtered;
    }

    /**
     * @param CategoryInterface $category
     * @param array $allCategoryIds
     * @return bool
     */
    private function isAllowed(CategoryInterface $category, array $allCategoryIds): bool
    {
        $allowedCategoryIds = $category->getChildrenCategories()->getAllIds();
        $allowedCategoryIds[] = $category->getId();

        return !!array_intersect($allowedCategoryIds, $allCategoryIds);
    }
}
