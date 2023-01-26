<?php

declare(strict_types=1);

namespace Ecommerce121\PartFinder\Block;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Layer\Resolver;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Framework\Serialize\Serializer\Json;
use Ecommerce121\PartFinder\Model\Store\Config;

class PartFinder extends Template
{
    /**
     * Url
     */
    private const ACTION_URL = '/ecommerce121_partfinder/index/partfinder';

    /**
     * @var CategoryCollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * @var Json
     */
    private $jsonManager;

    /**
     * @var
     */
    private $config;

    /**
     * @var Resolver
     */
    private $layerResolver;

    /**
     * @param Template\Context $context
     * @param CategoryCollectionFactory $categoryCollectionFactory
     * @param Json $json
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CategoryCollectionFactory $categoryCollectionFactory,
        Json $json,
        Config $config,
        Resolver $layerResolver,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->jsonManager = $json;
        $this->config = $config;
        $this->layerResolver = $layerResolver;
    }

    /**
     * @return DataObject[]
     */
    public function getTopCategories(): array
    {
        $firstCategoryCollection = $this->categoryCollectionFactory->create();
        $excludeCategoryIds = explode(',', $this->config->getExcludedCategories());
        $collection = $firstCategoryCollection->addFieldToSelect('name')->addFieldToFilter('level', 2);
        if (!empty($excludeCategoryIds)) {
            $collection = $firstCategoryCollection->addFieldToFilter('entity_id', ['nin' => $excludeCategoryIds]);
        }
        return $collection->getItems();
    }

    /**
     * @return bool
     */
    public function displayOnCurrentCategory(): bool
    {
        $category = $this->getCurrentCategory();

        return (in_array($category->getId(), $this->config->getCategoriesToDisplay()) ||
            $this->getNameInLayout() == 'ecommerce121_partfinder_search');
    }

    /**
     * @return Category
     */
    public function getCurrentCategory(): Category
    {
        return $this->layerResolver->get()->getCurrentCategory();
    }

    /**
     * @return string
     */
    public function getJsonOptions() : string
    {
        return $this->jsonManager->serialize([
            'url' => self::ACTION_URL,
        ]);
    }
}
