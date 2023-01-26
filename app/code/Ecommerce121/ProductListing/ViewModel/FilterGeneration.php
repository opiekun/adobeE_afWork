<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\ViewModel;

use Ecommerce121\ProductListing\Model\CategoryDataProvider;
use Ecommerce121\ProductListing\Model\Store\Config;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\App\RequestInterface;

class FilterGeneration implements ArgumentInterface
{
    const GENERATION_PARAM = 'genId';

    /**
     * @var Config
     */
    private $config;

    /**
     * @var CategoryDataProvider
     */
    private $categoryDataProvider;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var array
     */
    private $filters;

    /**
     * @var UrlInterface
     */
    private UrlInterface $url;

    /**
     * @param CategoryDataProvider $categoryDataProvider
     * @param RequestInterface $request
     * @param Config $config
     * @param UrlInterface $url
     */
    public function __construct(
        CategoryDataProvider $categoryDataProvider,
        RequestInterface $request,
        Config $config,
        UrlInterface $url
    ) {
        $this->categoryDataProvider = $categoryDataProvider;
        $this->request = $request;
        $this->config = $config;
        $this->url = $url;
    }

    /**
     * @return CategoryInterface|null
     */
    public function getCurrentCategory(): ?CategoryInterface
    {
        return $this->categoryDataProvider->getCurrentCategory();
    }

    /**
     * @TODO make method shortly. Try to simplify and divide tasks into multiple methods
     * @return array
     * @throws LocalizedException
     */
    public function getOptions(): array
    {
        $currentUrl = $this->url->getCurrentUrl();
        $currentCategory = $this->getCurrentCategory();
        $filters = $this->getFilters();
        $filterCategory = reset($filters);
        $filterGeneration = end($filters);
        $url = $currentCategory->getUrl() . '?' . CategoryDataProvider::PARAMETER . '=';

        $options[] = [
            'name' => __('Select a generation'),
            'url' => $url . $filterCategory,
            'selected' => '',
        ];

        $childrenCollection = $currentCategory->getChildrenCategories()->addAttributeToSelect('generation_label');
        $generationIds = $childrenCollection->getAllIds();

        foreach ($childrenCollection as $category) {
            $generationParam = !$this->getCurrentGenerationIdFromUrl()
                ? '&'.self::GENERATION_PARAM.'=' . $category->getId()
                : '';

            if ($selectedCategory = $this->getCurrentCategoryFromUrl()) {
                $selectedGenId = substr($selectedCategory, strrpos($selectedCategory, "-"));
                $selectedId = str_replace('-','', $selectedGenId);

                if (in_array($selectedId, $generationIds)) {
                    // selected category is a generation category
                    $urlKey = $category->getId();
                    $finalUrl = $url . $category->getUrlKey() . '-' . $category->getId();

                } else {
                    // Selected category isn't a generation category, concatenate generation id
                    if (strpos($currentUrl, ',') !== false) {
                        $currentUrl = str_replace(substr($currentUrl, strpos($currentUrl, ',')),'', $currentUrl);
                    }

                    $urlKey = ',' . $category->getId();
                    $generationParam = '&'.self::GENERATION_PARAM.'=' . $category->getId();
                    $finalUrl = $currentUrl . $urlKey;
                }
            } else {
                // No category selected
                $urlKey = $category->getId();
                $finalUrl = $url . $category->getUrlKey() . '-' . $category->getId();
            }

            $options[] = [
                'name' => $category->getData('generation_label') ?? $category->getName(),
                'url' => $finalUrl . $generationParam,
                'selected' => $filterGeneration === $urlKey ? 'selected' : '',
            ];
        }

        return $options;
    }

    /**
     * @return mixed
     */
    public function getCurrentCategoryFromUrl()
    {
        return $this->request->getParam(CategoryDataProvider::PARAMETER);
    }

    /**
     * @return mixed
     */
    public function getCurrentGenerationIdFromUrl()
    {
        return $this->request->getParam(self::GENERATION_PARAM);
    }

    /**
     * @return array
     */
    private function getFilters(): array
    {
        if (null === $this->filters) {
            $filters = $this->request->getParam(CategoryDataProvider::PARAMETER);
            $this->filters = $filters ? explode(',', $filters) : [];
        }

        return $this->filters;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->config->getGenerationFilterLabel();
    }
}
