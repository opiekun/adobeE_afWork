<?php

declare(strict_types=1);

namespace Ecommerce121\PartFinder\Model\Config\Source;

use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\DataObject;

class CategoryList implements OptionSourceInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var CategoryFactory
     */
    private $categoryFactory;

    /**
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        CategoryFactory $categoryFactory
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->categoryFactory = $categoryFactory;
    }

    /**
     * @return DataObject[]
     */
    public function getTopCategories(): array
    {
        $categoryCollection = $this->categoryCollectionFactory->create();
        $collection = $categoryCollection
            ->addAttributeToSelect('name')
            ->addFieldToFilter('level', [2,3,4]);

        $categoryList = [];
        foreach ($collection->getItems() as $category) {
            $categoryList[$category->getId()] = __($this->getParentName($category->getPath()) . $category->getName());
        }

        return $categoryList;
    }

    /**
     * @param $path
     * @return string
     */
    private function getParentName($path): string
    {
        $parentName = '';
        $rootCategories = [1, 2];
        $categoryTree = explode("/", $path);
        array_pop($categoryTree);

        if ($categoryTree && (count($categoryTree) > count($rootCategories))) {
            foreach ($categoryTree as $categoryId) {
                if (!in_array($categoryId, $rootCategories)) {
                    $category = $this->categoryFactory->create()->load($categoryId);
                    $categoryName = $category->getName();
                    $parentName .= $categoryName . ' > ';
                }
            }
        }

        return $parentName;
    }


    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        $options = [];
        $categories = $this->getTopCategories();
        arsort($categories);
        foreach ($categories as $key => $value)
        {
            $options[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $options;
    }
}
