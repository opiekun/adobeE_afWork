<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\Plugin\Magento\Catalog\Helper\Data;

use Ecommerce121\ProductListing\Model\CategoryDataProvider;
use Ecommerce121\ProductListing\Model\Store\Config;
use Magento\Catalog\Helper\Data;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\App\Request\Http;

class AddBreadcrumbs
{
    private const THE_LOWEST_LEVEL = 4;

    /**
     * @var CategoryDataProvider
     */
    private $categoryDataProvider;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    private $categoryPath;

    /**
     * @var Http
     */
    private Http $request;

    /**
     * @param CategoryDataProvider $categoryDataProvider
     * @param Config $config
     * @param Http $request
     */
    public function __construct(
        CategoryDataProvider $categoryDataProvider,
        Config $config,
        Http $request
    )
    {
        $this->categoryDataProvider = $categoryDataProvider;
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * @param Data $subject
     * @param callable $proceed
     * @return array
     */
    public function aroundGetBreadcrumbPath(Data $subject, callable $proceed): array
    {
        $category = $subject->getCategory();
        if ($subject->getProduct() || !$category) {
            return $proceed();
        }

        if ($this->categoryPath) {
            return $this->categoryPath;
        }
        $this->addCategoryPathBreadcrumb($category);

        $categoryIds = $this->categoryDataProvider->getParentFilteredCategoryIds();
        if ($categoryIds) {
            sort($categoryIds);
            foreach ($categoryIds as $categoryId) {
                $this->addCrumbsForCategoryId($category, $categoryId);
            }
        }

        $this->setGenerationBreadcrumbOrder();
        $this->removeLinkFromTheLastElement();
        if (is_array($this->categoryPath)) {
            return $this->categoryPath;
        }

        return [];
    }

    /**
     * @return void
     */
    private function setGenerationBreadcrumbOrder()
    {
        if ($genId = $this->request->getParam('genId')) {
            $categoryKey = 'category' . $genId;
            if (array_key_exists($categoryKey, $this->categoryPath)) {
                $generationArray[$categoryKey] = $this->categoryPath[$categoryKey];
                unset($this->categoryPath[$categoryKey]);
            }

            if (isset($generationArray)) {
                array_splice($this->categoryPath, 4,0, $generationArray);
            }
        }
    }

    /**
     * @param CategoryInterface $category
     */
    private function addCategoryPathBreadcrumb(CategoryInterface $category)
    {
        $pathInStore = $category->getPathInStore();
        $pathIds = array_reverse(explode(',', $pathInStore));
        $categories = $category->getParentCategories();

        foreach ($pathIds as $categoryId) {
            if (isset($categories[$categoryId]) && $categories[$categoryId]->getName()) {
                $this->categoryPath['category' . $categoryId] = [
                    'label' => $categories[$categoryId]->getName(),
                    'link' => $categories[$categoryId]->getUrl(),
                ];
            }
        }
    }

    /**
     * @param CategoryInterface $currentCategory
     * @param int $categoryId
     */
    private function addCrumbsForCategoryId(CategoryInterface $currentCategory, int $categoryId)
    {
        $category = $this->categoryDataProvider->getCategoryById($categoryId);
        if ($category) {
            if ($this->isProductCategory($category)) {
                if ($category->getLevel() == self::THE_LOWEST_LEVEL) {
                    $link = $this->categoryDataProvider->buildUrlParams(
                        $currentCategory->getUrl(),
                        [$category->getParentCategory()->getId() => $category->getParentCategory()->getUrlKey()]
                    );

                    $this->categoryPath['category' . $category->getParentCategory()->getId()] = [
                        'label' => $category->getParentCategory()->getName(),
                        'link' => $link,
                    ];
                }
                $link = $this->categoryDataProvider->buildUrlParams(
                    $currentCategory->getUrl(),
                    [$category->getId() => $category->getUrlKey()]
                );
                $this->categoryPath['category' . $categoryId] = [
                    'label' => $category->getName(),
                    'link' => $link,
                ];
            } else {
                $this->categoryPath['category' . $categoryId] = [
                    'label' => $category->getName(),
                    'link' => $category->getUrl(),
                ];
            }
        }
    }

    /**
     * @param CategoryInterface $category
     * @return bool
     */
    private function isProductCategory(CategoryInterface $category): bool
    {
        $pathIds =  explode(',', $category->getPathInStore());
        $category = $this->categoryDataProvider->getCategoryById($this->config->getDefaultProductCategoryId());
        if (!$category instanceof CategoryInterface) {
            return false;
        }

        if (array_intersect($pathIds, $category->getChildrenCategories()->getAllIds())) {
            return true;
        }

        return false;
    }

    /**
     * @return void
     */
    private function removeLinkFromTheLastElement()
    {
        if (is_array($this->categoryPath)) {
            $lastElement = array_pop($this->categoryPath);
            if (isset($lastElement['link'])) {
                $lastElement['link'] = '';
            }

            $this->categoryPath[] = $lastElement;
        }
    }
}
