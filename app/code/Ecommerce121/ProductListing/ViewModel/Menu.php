<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\ViewModel;

use Amasty\Finder\Api\DropdownRepositoryInterface;
use Amasty\Finder\Api\ValueRepositoryInterface;
use Amasty\Finder\Helper\Config as FinderConfig;
use Amasty\Finder\Model\Session;
use Ecommerce121\ProductListing\Model\CategoryDataProvider;
use Ecommerce121\ProductListing\Model\ResourceModel\Catalog;
use Ecommerce121\ProductListing\Model\Store\Config;
use Ecommerce121\ProductListing\Model\VehicleCategory;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Customer\Model\Session as CustomerSession;

class Menu implements ArgumentInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var CategoryDataProvider
     */
    private $categoryDataProvider;

    /**
     * @var bool
     */
    private $isProductType;

    /**
     * @var VehicleCategory
     */
    private $vehicleCategory;

    /**
     * @var array|null
     */
    private $middleLevelItems;

    /**
     * @var array|null
     */
    private $activeCategoryIds;

    /**
     * @var Catalog
     */
    private $catalog;

    /**
     * @var UrlInterface
     */
    private UrlInterface $url;

    /**
     * @var Session
     */
    private $finderSession;

    /**
     * @var Http
     */
    private $request;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var FinderConfig
     */
    private $finderConfig;

    /**
     * @var ValueRepositoryInterface
     */
    private $valueRepository;

    /**
     * @var string
     */
    private string $currentTitle;

    /**
     * @var DropdownRepositoryInterface
     */
    private DropdownRepositoryInterface $dropdownRepository;

    /**
     * @param Config $config
     * @param CategoryDataProvider $categoryDataProvider
     * @param VehicleCategory $vehicleCategory
     * @param Catalog $catalog
     * @param UrlInterface $url
     * @param Session $finderSession
     * @param Http $request
     * @param ProductRepositoryInterface $productRepository
     * @param FinderConfig $finderConfig
     * @param ValueRepositoryInterface $valueRepository
     * @param DropdownRepositoryInterface $dropdownRepository
     */
    public function __construct(
        Config $config,
        CategoryDataProvider $categoryDataProvider,
        VehicleCategory $vehicleCategory,
        Catalog $catalog,
        UrlInterface $url,
        Session $finderSession,
        Http $request,
        ProductRepositoryInterface $productRepository,
        FinderConfig $finderConfig,
        ValueRepositoryInterface $valueRepository,
        DropdownRepositoryInterface $dropdownRepository,
        CustomerSession $customerSession
    ) {
        $this->config = $config;
        $this->categoryDataProvider = $categoryDataProvider;
        $this->vehicleCategory = $vehicleCategory;
        $this->catalog = $catalog;
        $this->url = $url;
        $this->finderSession = $finderSession;
        $this->request = $request;
        $this->productRepository = $productRepository;
        $this->finderConfig = $finderConfig;
        $this->valueRepository = $valueRepository;
        $this->currentTitle = '';
        $this->dropdownRepository = $dropdownRepository;
        $this->customerSession = $customerSession;
    }

    /**
     * @return bool
     */
    public function isProductType(): bool
    {
        if (null === $this->isProductType) {
            if (!$this->config->isEnabled() || !($categoryIds = $this->getActiveCategoryIds())) {
                $this->isProductType = false;
                return $this->isProductType;
            }

            $categoriesWithProductMode = $this->config->getProductModeCategoryIds();
            $this->isProductType = !!array_intersect($categoryIds, $categoriesWithProductMode);
        }

        return $this->isProductType;
    }

    /**
     * @return CategoryInterface|null
     */
    public function getCurrentCategory(): ?CategoryInterface
    {
        $currentCategory = $this->categoryDataProvider->getCurrentCategory();
        if ($currentCategory !== null) {
            return $currentCategory;
        } else {
            return $this->categoryDataProvider->getCategoryById($this->config->getDefaultProductCategoryId());
        }
    }

    /**
     * @return array
     */
    public function getMiddleLevelItems($loadedProductCollection=NULL): array
    {
        if (null === $this->middleLevelItems) {
            try {
                $productModeCategoryId = $this->config->getDefaultProductCategoryId();
                $category = $this->categoryDataProvider->getCategoryById($productModeCategoryId);

                if (!$category) {
                    throw new LocalizedException(__('Category for product mode is not set'));
                }
                $this->middleLevelItems = $this->filterCategories($category->getChildrenCategories()->getItems(), $loadedProductCollection);
            } catch (LocalizedException $e) {
                $this->middleLevelItems = [];
            }
        }

        return $this->middleLevelItems;
    }

    /**
     * @param $middleLevelActiveId
     * @param $productCollection
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getLowLevelFinderItems($middleLevelActiveId, $productCollection): array
    {
        $category = null;
        if ($middleLevelActiveId) {
            $category = $this->categoryDataProvider->getCategoryById($middleLevelActiveId);

            $finderParams = $this->request->getParam('find', null);
            if ($finderParams) {
                return $this->filterLowerCategoriesByFinderResults(
                    $category->getChildrenCategories()->addAttributeToSelect('thumbnail')->getItems()
                );
            }

            foreach ($this->getMiddleLevelItems() as $item) {
                if (!in_array($item->getId(), $this->getActiveCategoryIds())) {
                    continue;
                }

                return $this->filterCategories(
                    $item->getChildrenCategories()->addAttributeToSelect('thumbnail')->getItems()
                );
            }
        }


        if (!$category instanceof CategoryInterface) {
            return [];
        }

        return $this->filterLowerCategories(
            $category->getChildrenCategories()->addAttributeToSelect('thumbnail')->getItems(), $productCollection
        );
    }

    /**
     * @return array
     * @throws LocalizedException
     */
    public function getLowLevelItems(): array
    {
        foreach ($this->getMiddleLevelItems() as $item) {
            if (!in_array($item->getId(), $this->getActiveCategoryIds())) {
                continue;
            }

            return $this->filterCategories(
                $item->getChildrenCategories()->addAttributeToSelect('thumbnail')->getItems()
            );
        }

        $category = current($this->getMiddleLevelItems());
        if (!$category instanceof CategoryInterface) {
            return [];
        }

        return $this->filterCategories(
            $category->getChildrenCategories()->addAttributeToSelect('thumbnail')->getItems()
        );
    }

    /**
     * @param array $items
     * @param $isSubCat
     * @return int|null
     */
    public function getActiveId(array $items, bool $isSubCat = null): ?int
    {
        if ($isSubCat && !$this->categoryDataProvider->getCurrentCategory()) {
            return null;
        }

        foreach ($items as $item) {
            if (in_array($item->getId(), $this->getActiveCategoryIds())) {
                return (int) $item->getId();
            }
        }

        $category = current($items);
        if (!$category instanceof CategoryInterface) {
            return null;
        }

        return (int) $category->getId();
    }

    /**
     * @param array $categories
     * @return array
     */
    private function filterCategories(array $categories, array $loadedProductCollection=NULL): array
    {
        if ($this->isProductType()) {
            $filteredCategories = [];
            $amfindCategories = [];

            foreach ($categories as $category) {
                $productIds = $category->getProductCollection()->getAllIds();

                if($loadedProductCollection) {
                    foreach ($loadedProductCollection as $product) {
                        $findedProductIds[] = $product->getId();
                    }

                    foreach($findedProductIds as $findedId) {
                        if(in_array($findedId,$productIds)) {

                            /**
                             * remember all categories found by amfinder original search
                             */
                            $currentMiddleLevelCategories = $this->customerSession->getCurrentMiddleLevelCategories();
                            $currentMiddleLevelCategories[$this->request->getParam('find')][$category->getId()] = $category->getId();
                            $this->customerSession->setCurrentMiddleLevelCategories($currentMiddleLevelCategories);

                            /**
                             * only show categories with products that were found by amfinder original search
                             */
                            $amfindCategories = $this->customerSession->getCurrentMiddleLevelCategories()[$this->request->getParam('find')];
                            if(in_array($category->getId(),$amfindCategories)) {
                                $filteredCategories[$category->getId()] = $category;
                            }

                        }
                    }

                }else {
                    if (count($productIds) > 0) {
                        $filteredCategories[] = $category;
                    }
                }
            }


            /**
             *  check that all categories found by amfinder original search are loaded
             *
             */
            $currentMiddleLevelCategories = $this->customerSession->getCurrentMiddleLevelCategories();
            if(is_array($currentMiddleLevelCategories) && count($currentMiddleLevelCategories) && $this->request->getParam('find')) {
                $amfindCategories = $currentMiddleLevelCategories[$this->request->getParam('find')] ?? null;
            }

            if( is_array($amfindCategories) && (count($filteredCategories) < count($amfindCategories)) ) {
                foreach($amfindCategories as $catId) {
                    $filteredCategories[$catId] = $this->categoryDataProvider->getCategoryById((int)$catId);
                }
            }

            usort($filteredCategories, function($a, $b) {
                return $a->getData('position') <=> $b->getData('position');
            });

            return $filteredCategories;
        }

        return $this->vehicleCategory->filter($categories);
    }

    /**
     * @param array $categories
     * @param $productCollection
     * @return array
     */
    private function filterLowerCategories(array $categories, $productCollection): array
    {
        if ($this->isProductType()) {
            $productIds = [];
            foreach ($productCollection as $product) {
                $productIds[] = $product->getId();
            }

            $categoryIds = $this->catalog->getCategoryIdsByProductIds($productIds);

            $filteredCategories = [];
            foreach ($categories as $category) {
                if (in_array($category->getId(), $categoryIds)) {
                    $filteredCategories[] = $category;
                }
            }

            return $filteredCategories;
        }

        return $this->vehicleCategory->filter($categories);
    }


    /**
     * @param array $categories
     * @return array
     * @throws NoSuchEntityException
     */
    private function filterLowerCategoriesByFinderResults(array $categories): array
    {
        $filteredCategories = [];

        $finderId = $this->getFinderId();
        $data = $this->finderSession->getFinderData($finderId);
        if ($data) {
            $skus = $data['sku'];
            $productIds = [];
            foreach ($skus as $sku) {
                try {
                    $productIds[] = $this->productRepository->get($sku)->getId();
                } catch (NoSuchEntityException $exception) {
                    continue;
                }
            }

            $categoryIds = $this->catalog->getCategoryIdsByProductIds($productIds);
            foreach ($categories as $category) {
                if (in_array($category->getId(), $categoryIds)) {
                    $filteredCategories[] = $category;
                }
            }
        }

        return $filteredCategories;
    }

    /**
     * @param CategoryInterface $category
     * @return string
     */
    public function getUrl(CategoryInterface $category): string
    {
        if ($this->isProductType()) {
            return $category->getUrl();
        }

        return $this->categoryDataProvider->buildUrlParams(
            $this->getCurrentCategory()->getUrl(),
            [$category->getId() => $category->getUrlKey()]
        );
    }

    /**
     * @param CategoryInterface $category
     * @return string
     */
    public function buildCategoryProductTypeUrl(CategoryInterface $category): string
    {
        $generationId = $this->getGenerationCategoryIdFromUrl($category);
        $selectedGen = ($generationId) ? ',' . $generationId . '&genId=' . $generationId : '';

        return $this->getUrl($category) . $selectedGen;
    }

    /**
     * @param CategoryInterface $category
     * @return string
     */
    public function buildFilterCategory(CategoryInterface $category): string
    {
        $currentUrl = $this->url->getCurrentUrl();
        $keyParameter = CategoryDataProvider::PARAMETER . '=';

        $paramExists = false;
        if (strpos($currentUrl, $keyParameter) !== false) {
            $currentUrl = preg_replace('/()category=.*?(&|$)/', '', $currentUrl);
        }
        if (strpos($currentUrl, '&') !== false) {
            $paramExists = true;
        }

        if (strpos($currentUrl, '&p') !== false
            || strpos($currentUrl, '&product_list_order') !== false
            || strpos($currentUrl, '&product_list_dir') !== false) {
            $paramExists = false;
        }

        $keyParameter = (!$paramExists) ? '&'. $keyParameter : $keyParameter;
        $urlKey = $category->getUrlKey() . '-' . $category->getId();

        $finalUrl = $currentUrl . $keyParameter;

        return $finalUrl . $urlKey;
    }


    /**
     * @param CategoryInterface $category
     * @return string|null
     */
    public function getGenerationCategoryIdFromUrl(CategoryInterface $category): ?string
    {
        $currentGenerationId = '';
        $currentUrl = $this->url->getCurrentUrl();
        $currentCategoryKey = (string) $category->getId();
        $selectedGeneration = $this->request->getParam('genId', null);

        if ($selectedGeneration) {
            return $selectedGeneration;
        }

        if (strpos($currentUrl, ',') !== false && !strpos($currentUrl, $currentCategoryKey)) {
            $currentGenerationId = substr($currentUrl, strpos($currentUrl, ','));
        }

        return $currentGenerationId;
    }

    /**
     * @return array
     */
    private function getActiveCategoryIds(): array
    {
        if (null === $this->activeCategoryIds) {
            $this->activeCategoryIds = [];
            $category = $this->getCurrentCategory();
            if (!$category) {
                return $this->activeCategoryIds;
            }

            $filteredCategoryIds = [];
            $filteredCategories = $this->categoryDataProvider->getFilteredCategories();
            array_walk($filteredCategories, function ($category) use (&$filteredCategoryIds) {
                $filteredCategoryIds = array_merge($filteredCategoryIds, array_keys($category->getParentCategories()));
            });

            $this->activeCategoryIds = array_merge(
                array_keys($category->getParentCategories()),
                array_diff($filteredCategoryIds, $this->config->getProductModeCategoryIds()),
                $this->categoryDataProvider->getFilteredCategoryIds()
            );
            $this->activeCategoryIds[] = $category->getId();
        }

        return $this->activeCategoryIds;
    }

    /**
     * @return int
     */
    private function getFinderId(): int
    {
        $finderId = $this->finderConfig->getConfigValue('advanced/compatible_finder');

        if (!$finderId) {
            $activeFinders = $this->finderSession->getAllFindersData();
            if ($activeFinders) {
                $finderIds = array_keys($activeFinders);
                $finderId = array_shift($finderIds);
            }
        }

        return (int) $finderId;
    }

    /**
     * @param $block
     * @param $activeCategoryId
     * @return void
     */
    public function setTitle($block, $activeCategoryId)
    {
        $title = $this->obtainTitle();

        $pageMainTitle = $block->getLayout()->getBlock('page.main.title');
        $pageMainTitle->setPageTitle($title);

        $this->currentTitle = $title;
    }


    /**
     * @return string
     */
    public function obtainTitle(): string
    {
        $title = '';
        $finderId = $this->getFinderId();
        $finderParams = $this->finderSession->getFinderData($finderId);

        if ($finderParams) {
            $finderParams['names'] = $this->getDropdownsByCurrent($finderParams['current']);
            $finderNames = array_reverse($finderParams['names']);
            if (array_key_exists(3, $finderNames)
                && array_key_exists(4, $finderNames)) {
                $tempFuel = $finderNames[3];
                $tempEngine = $finderNames[4];
                $finderNames[4] = $tempFuel;
                $finderNames[3] = $tempEngine;
            }

            $title = strtoupper(implode(" ", $finderNames));
        }

        $categoryIds = $this->categoryDataProvider->getParentFilteredCategoryIds();
        $categoryStrings = [];
        foreach ($categoryIds as $categoryId) {
            $category = $this->categoryDataProvider->getCategoryById((int)$categoryId);

            if ($this->request->getParam(CategoryDataProvider::PARAMETER)) {
                $categoryUrl = str_replace("products", "", $category->getUrlPath());
                $url = strtoupper(str_replace("/", " / ", str_replace("-", " ", $categoryUrl)));
                $url = strtoupper(str_replace(["/","-"], [" / ", " "], $categoryUrl));

                $categoryStrings[] = $url;
            }
        }
        $title .= join(' / ', array_unique($categoryStrings));

        return $title;
    }

    /**
     * Get finder dropdown values
     *
     * @param $current
     * @return array
     */
    public function getDropdownsByCurrent($current): array
    {
        $dropdowns = [];
        while ($current) {
            try {
                $valueModel = $this->valueRepository->getById($current);
                $dropdownId = $valueModel->getDropdownId();
                $dropdown = $this->dropdownRepository->getById($dropdownId);
                if ($dropdown->getName() !== 'Chassis Code') {
                    $dropdowns[$dropdownId] = $valueModel->getName();
                }

                $current = $valueModel->getParentId();
            } catch (NoSuchEntityException $e) {
                $current = false;
            }
        }

        return $dropdowns;
    }

}
