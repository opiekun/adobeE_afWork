<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class CategoryDataProvider
{
    public const PARAMETER = 'category';

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var CategoryInterface|bool
     */
    private $currentCategory;

    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * @var CategoryInterface[]
     */
    private $filteredCategories;

    /**
     * @var int[]
     */
    private $filteredCategoryIds;

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param RequestInterface $request
     * @param CategoryCollectionFactory $categoryCollectionFactory
     */
    public function __construct(
        CategoryRepositoryInterface $categoryRepository,
        RequestInterface $request,
        CategoryCollectionFactory $categoryCollectionFactory
    ) {
        $this->request = $request;
        $this->categoryRepository = $categoryRepository;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * @return CategoryInterface|null
     */
    public function getCurrentCategory(): ?CategoryInterface
    {
        if (null === $this->currentCategory) {
            $this->currentCategory = $this->getCategoryById($this->getCurrentCategoryId()) ?? null;
        }

        return $this->currentCategory;
    }

    /**
     * @return int
     */
    public function getCurrentCategoryId(): int
    {
        return (int)$this->request->getParam('id');
    }

    /**
     * @return int[]
     */
    public function getFilteredCategoryIds(): array
    {
        if (null === $this->filteredCategoryIds) {
            $this->filteredCategoryIds = [];
            $filters = $this->getParentFilteredCategoryIds();
            if (!$filters) {
                return $this->filteredCategoryIds;
            }

            foreach ($filters as $parentId) {
                $this->filteredCategoryIds[] = $parentId;
                $this->addChildrenCategoriesIds($parentId);
            }
        }

        return $this->filteredCategoryIds;
    }

    /**
     * @return array
     */
    public function getParentFilteredCategoryIds(): array
    {
        $filters = $this->request->getParam(self::PARAMETER);
        if (!$filters) {
            return [];
        }

        $urlKeys = explode(',', $filters);
        $categoryIds = [];

        array_walk($urlKeys, function ($item) use (&$categoryIds) {
            $tmp = explode('-', $item);
            $categoryIds[] = (int)end($tmp);
        });

        return $categoryIds;
    }

    /**
     * @param string $url
     * @param array $params
     * @return string
     */
    public function buildUrlParams(string $url, array $params)
    {
        $query = [];
        foreach ($params as $categoryId => $urlKey) {
            $query[] = $urlKey . '-' . $categoryId;
        }

        return $url . '?' . CategoryDataProvider::PARAMETER . '=' . implode(',', $query);
    }

    /**
     * @param int $parentId
     * @return void
     */
    private function addChildrenCategoriesIds(int $parentId)
    {
        try {
            $category = $this->categoryRepository->get($parentId);
            $this->filteredCategoryIds = array_merge(
                $this->filteredCategoryIds,
                $category->getChildrenCategories()->getAllIds()
            );
        } catch (LocalizedException $e) {}
    }

    /**
     * @return CategoryInterface[]
     */
    public function getFilteredCategories(): array
    {
        if (null === $this->filteredCategories) {
            $ids = $this->getFilteredCategoryIds();

            $this->filteredCategories = [];
            if ($ids) {
                $collection = $this->categoryCollectionFactory->create();
                $this->filteredCategories = $collection
                    ->addFieldToFilter($collection->getIdFieldName(), ['in' => $ids])
                    ->getItems();
            }
        }

        return $this->filteredCategories;
    }

    /**
     * @param int $categoryId
     * @return null|CategoryInterface
     */
    public function getCategoryById(int $categoryId): ?CategoryInterface
    {
        try {
            return $this->categoryRepository->get($categoryId);
        } catch (LocalizedException $e) {
            return null;
        }
    }
}
