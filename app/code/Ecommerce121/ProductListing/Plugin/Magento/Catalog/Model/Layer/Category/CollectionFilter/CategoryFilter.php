<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\Plugin\Magento\Catalog\Model\Layer\Category\CollectionFilter;

use Ecommerce121\ProductListing\Model\ResourceModel\Catalog;
use Ecommerce121\ProductListing\Model\CategoryDataProvider;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Layer\Category\CollectionFilter;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\Request\Http;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Layout;

class CategoryFilter
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
     * @var Http
     */
    private Http $request;

    /**
     * @param CategoryDataProvider $categoryDataProvider
     * @param Catalog $catalog
     * @param Http $request
     */
    public function __construct(
        CategoryDataProvider $categoryDataProvider,
        Catalog $catalog,
        Http $request
    ) {
        $this->categoryDataProvider = $categoryDataProvider;
        $this->catalog = $catalog;
        $this->request = $request;
    }

    /**
     * @param CollectionFilter $subject
     * @param null $result
     * @param Collection $collection
     * @param Category $category
     * @return null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterFilter(
        CollectionFilter $subject,
        $result,
        Collection $collection,
        Category $category
    ) {
        $filteredCategoryIds = $this->categoryDataProvider->getFilteredCategoryIds();
        if ($filteredCategoryIds) {
            $productIds = $this->catalog->getProductIdsByCategoryIds($filteredCategoryIds);
            $collection->getSelect()->where('e.entity_id IN (?)', $productIds);
        }

        $this->addSortOrderFilter($collection);

        return $result;
    }

    /**
     * @param Collection $collection
     * @return Collection
     */
    public function addSortOrderFilter(Collection $collection): Collection
    {
        if ($this->request->getModuleName() == 'amfinder') {
            $defaultSortOrderParam = 'afe_product_ranking';
        } else {
            $defaultSortOrderParam = 'created_at';
        }

        $sortOrderParam = $this->request->getParam('product_list_order', $defaultSortOrderParam);
        $sortDirectionParam = $this->request->getParam('product_list_dir', 'ASC');
        $sortLimitParam = $this->request->getParam('product_list_limit', 24);
        $currentPage = $this->request->getParam('p', 1);

        if ($sortOrderParam == 'afe_product_ranking') {
            $tableCatalogProductEntityInt = $collection->getResource()->getTable('catalog_product_entity_int');
            $tableEavAttribute = $collection->getResource()->getTable('eav_attribute');
            $collection->getSelect()->columns(
                array("afe_product_ranking" =>
                    new \Zend_Db_Expr(
                        "(SELECT $tableCatalogProductEntityInt.value 
                        FROM $tableCatalogProductEntityInt 
                        INNER JOIN $tableEavAttribute ON $tableEavAttribute.attribute_id=$tableCatalogProductEntityInt.attribute_id 
                        AND $tableEavAttribute.attribute_code='afe_product_ranking' 
                        WHERE e.row_id=$tableCatalogProductEntityInt.row_id)"
                    )
                )
            );
            $collection->getSelect()->order("afe_product_ranking $sortDirectionParam");
        }else {
            $collection->getSelect()->order('e.' . $sortOrderParam . ' ' . $sortDirectionParam);
        }

        $collection->getSelect()->limitPage($currentPage, $sortLimitParam);

        return $collection;
    }
}
