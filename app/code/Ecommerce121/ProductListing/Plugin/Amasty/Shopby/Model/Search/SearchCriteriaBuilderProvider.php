<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\Plugin\Amasty\Shopby\Model\Search;

use Amasty\Shopby\Model\Search\SearchCriteriaBuilderProvider as AmastySearchCriteriaBuilderProvider;
use Ecommerce121\ProductListing\Model\CategoryDataProvider;
use Ecommerce121\ProductListing\Model\ResourceModel\Catalog;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilder;
use Magento\Framework\Api\Search\SearchCriteriaBuilderFactory;
use Magento\Framework\App\Request\Http;

class SearchCriteriaBuilderProvider extends AmastySearchCriteriaBuilderProvider
{
    /**
     * @var SearchCriteriaBuilderFactory
     */
    private SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory;

    /**
     * @var FilterBuilder
     */
    private FilterBuilder $filterBuilder;

    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var CategoryDataProvider
     */
    private CategoryDataProvider $categoryDataProvider;

    /**
     * @var Catalog
     */
    private Catalog $catalog;

    /**
     * @var Http
     */
    private Http $request;

    /**
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param FilterBuilder $filterBuilder
     * @param CategoryDataProvider $categoryDataProvider
     * @param Catalog $catalog
     * @param Http $request
     */
    public function __construct(
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        FilterBuilder $filterBuilder,
        CategoryDataProvider $categoryDataProvider,
        Catalog $catalog,
        Http $request
    ) {
        parent::__construct($searchCriteriaBuilderFactory, $filterBuilder);
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->filterBuilder = $filterBuilder;
        $this->categoryDataProvider = $categoryDataProvider;
        $this->catalog = $catalog;
        $this->request = $request;
    }


    /**
     * @param string $field
     * @param string|array $value
     * @return $this
     */
    public function addFilter(string $field, $value): AmastySearchCriteriaBuilderProvider
    {
        $this->filters[$field] = $value;
        return $this;
    }

    /**
     * @param string $field
     * @return void
     */
    public function removeFilter(string $field): void
    {
        unset($this->filters[$field]);
    }


    /**
     * @return SearchCriteriaBuilder
     */
    public function create(): SearchCriteriaBuilder
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $finderParams = $this->request->getParam('find', null);
        if ($finderParams) {
            $this->removeFilter('category_ids');
        }

        $this->addFinderFilter();
        $this->addCategoryFilter($searchCriteriaBuilder);

        foreach ($this->filters as $field => $value) {
            $this->filterBuilder->setField($field);
            $this->filterBuilder->setValue($value);
            $searchCriteriaBuilder->addFilter($this->filterBuilder->create());
        }

        return $searchCriteriaBuilder;
    }

    /**
     * @return void
     */
    public function addFinderFilter()
    {
        $finderParams = $this->request->getParam('find', null);
        if (!$finderParams) {
            $filteredCategoryIds = $this->categoryDataProvider->getFilteredCategoryIds();
            if ($filteredCategoryIds) {
                $productSkus = $this->catalog->getSkuByProductIds(
                    $this->catalog->getProductIdsByCategoryIds($filteredCategoryIds)
                );
                $this->addFilter('sku', $productSkus);
            }
        }
    }

    /**
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @return SearchCriteriaBuilder
     */
    public function addCategoryFilter(
        SearchCriteriaBuilder $searchCriteriaBuilder
    ): SearchCriteriaBuilder {
        $filteredCategoryIds = $this->categoryDataProvider->getFilteredCategoryIds();
        if ($filteredCategoryIds) {
            $filter = $this->filterBuilder
                ->setField('category_ids')
                ->setConditionType('eq')
                ->setValue($filteredCategoryIds)
                ->create();

            $searchCriteriaBuilder->addFilter($filter);
        }

        return $searchCriteriaBuilder;
    }
}
