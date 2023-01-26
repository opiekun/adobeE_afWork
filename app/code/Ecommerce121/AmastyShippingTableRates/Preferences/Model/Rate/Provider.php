<?php

declare(strict_types=1);

namespace Ecommerce121\AmastyShippingTableRates\Preferences\Model\Rate;

use Amasty\ShippingTableRates\Model\ConfigProvider;
use Amasty\ShippingTableRates\Model\Method;
use Amasty\ShippingTableRates\Model\Rate;
use Amasty\ShippingTableRates\Model\Rate\CostCalculator;
use Amasty\ShippingTableRates\Model\Rate\ItemsTotalCalculator;
use Amasty\ShippingTableRates\Model\Rate\ItemValidator;
use Amasty\ShippingTableRates\Model\Rate\Provider as AmastyProvider;
use Amasty\ShippingTableRates\Model\ResourceModel\Method\Collection as MethodCollection;
use Amasty\ShippingTableRates\Model\ResourceModel\Rate as RateResource;
use Ecommerce121\AmastyShippingTableRates\Plugin\Amasty\ShippingTableRates\Helper\Data;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Quote\Model\Quote\Item;

class Provider extends AmastyProvider
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ItemValidator
     */
    private ItemValidator $itemValidator;

    /**
     * @var RateResource
     */
    private RateResource $rateResource;

    /**
     * @var ConfigProvider
     */
    private ConfigProvider $configProvider;

    /**
     * @var ItemsTotalCalculator
     */
    private ItemsTotalCalculator $itemsTotalCalculator;

    /**
     * @var CostCalculator
     */
    private CostCalculator $costCalculator;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param RateResource $rateResource
     * @param ConfigProvider $configProvider
     * @param ItemsTotalCalculator $itemsTotalCalculator
     * @param CostCalculator $costCalculator
     * @param ItemValidator $itemValidator
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        RateResource $rateResource,
        ConfigProvider $configProvider,
        ItemsTotalCalculator $itemsTotalCalculator,
        CostCalculator $costCalculator,
        ItemValidator $itemValidator
    ) {
        parent::__construct(
            $productRepository,
            $rateResource,
            $configProvider,
            $itemsTotalCalculator,
            $costCalculator,
            $itemValidator
        );

        $this->productRepository = $productRepository;
        $this->itemValidator = $itemValidator;
        $this->rateResource = $rateResource;
        $this->configProvider = $configProvider;
        $this->itemsTotalCalculator = $itemsTotalCalculator;
        $this->costCalculator = $costCalculator;
    }


    /**
     * @param RateRequest $request
     * @param MethodCollection $collection
     *
     * @return array
     * @throws NoSuchEntityException
     */
    public function getRates(RateRequest $request, MethodCollection $collection): array
    {
        if (!$request->getAllItems() || !$collection->getSize()) {
            return [];
        }

        $methodIds = [];
        foreach ($collection as $method) {
            $methodIds[] = $method->getId();
        }

        $itemsShippingTypes = $this->getShippingTypes($request);
        $shippingTypes = array_merge($itemsShippingTypes, [Rate::ALL_VALUE]);
        $rateTypes = $this->rateResource->getUniqueRateTypes($methodIds, $shippingTypes);
        $cleanTotals = $this->itemsTotalCalculator->execute($request, Rate::ALL_VALUE);

        $allCosts = [];
        $freeTypes = [];
        $collectedTypes = [];

        foreach ($rateTypes as $methodId => $methodShippingTypes) {
            /** @var Method $method */
            $method = $collection->getItemById($methodId);
            $freeTypes[$methodId] = $method->getFreeTypes();
            $allTotals = $cleanTotals;

            foreach ($methodShippingTypes as $shippingType) {
                if ($shippingType !== Rate::ALL_VALUE) {
                    $totals = $this->itemsTotalCalculator->execute($request, $shippingType);
                } else {
                    $totals = $allTotals;
                }

                if (!($totals['not_free_qty'] > 0) && !($totals['qty'] > 0)) {
                    continue;
                }

                if ($allTotals['qty'] > 0
                    && (!$this->configProvider->getDontSplit() || $allTotals['qty'] === $totals['qty'])
                ) {
                    $totals['not_free_weight'] = $this->getWeightForUse($method, $totals);
                    $allTotals = $this->changeAllTotalsCapacity($allTotals, $totals);
                    $ratesData = $this->rateResource->getMethodRates(
                        $request,
                        $methodId,
                        $totals,
                        $shippingType,
                        $this->configProvider->isPromoAllowed()
                    );
                    $calculatedCost = $this->costCalculator->calculateCosts($request, $collection, $ratesData, $totals);

                    if (empty($calculatedCost)) {
                        continue;
                    }

                    if (empty($allCosts[$methodId])) {
                        $allCosts[$methodId]['cost'] = $calculatedCost['cost'];
                        $allCosts[$methodId]['time'] = $calculatedCost['time'];
                        $allCosts[$methodId]['name_delivery'] = $calculatedCost['name_delivery'];
                    } else {
                        $allCosts = $this->costCalculator->setCostTime($method, $allCosts, $calculatedCost);
                    }
                    $collectedTypes[$methodId][] = $shippingType;
                }
            }
        }

        return $this->unsetUnnecessaryCosts($allCosts, $itemsShippingTypes, $collectedTypes, $freeTypes);
    }

    /**
     * @param array $allCosts
     * @param array $shippingTypes
     * @param array $collectedTypes
     * @param array $freeTypes
     * @return array
     */
    private function unsetUnnecessaryCosts(
        array $allCosts,
        array $shippingTypes,
        array $collectedTypes,
        array $freeTypes
    ): array {
        foreach ($allCosts as $key => $cost) {
            if (in_array(Rate::ALL_VALUE, $collectedTypes[$key])) {
                continue;
            }

            $extraTypes = array_diff($shippingTypes, $collectedTypes[$key]);
            if (!$extraTypes) {
                continue;
            }

            if (!array_diff($extraTypes, $freeTypes[$key])) {
                continue;
            }

            unset($allCosts[$key]);
        }

        return $allCosts;
    }

    /**
     * @param array $allTotals
     * @param array $currentTotals
     * @return array
     */
    private function changeAllTotalsCapacity(array $allTotals, array $currentTotals): array
    {
        $allTotals['not_free_price'] = $allTotals['not_free_price'] - $currentTotals['not_free_price'];
        $allTotals['not_free_weight'] = $allTotals['not_free_weight'] - $currentTotals['not_free_weight'];
        $allTotals['not_free_volumetric'] =
            $allTotals['not_free_volumetric'] - $currentTotals['not_free_volumetric'];
        $allTotals['not_free_qty'] = $allTotals['not_free_qty'] - $currentTotals['not_free_qty'];
        $allTotals['qty'] = $allTotals['qty'] - $currentTotals['qty'];

        return $allTotals;
    }


    /**
     * @param RateRequest $request
     * @return array
     * @throws NoSuchEntityException
     */
    private function getShippingTypes(RateRequest $request): array
    {
        $shippingTypes = [];

        /** @var Item $item */
        foreach ($request->getAllItems() as $item) {
            if ($item->getParentItemId()) {
                continue;
            }

            if ($this->itemValidator->isShouldProcessChildren($item)) {
                foreach ($item->getChildren() as $child) {
                    $productId = $child->getProductId();
                }
            } else {
                $productId = $item->getProductId();
            }

            $product = $this->productRepository->getById($productId);

            if (!$product->getAmShippingType()) {
                $shippingTypes[] = Data::WITHOUT_SHIPPING_TYPE_VALUE;
            } else {
                if ($product->getAmShippingType()) {
                    $shippingTypes[] = $product->getAmShippingType();
                } else {
                    $shippingTypes[] = Rate::ALL_VALUE;
                }
            }
        }

        return array_unique($shippingTypes);
    }

    /**
     * @param Method $method
     * @param array $totals
     * @return float
     */
    private function getWeightForUse(Method $method, array $totals): float
    {
        switch ($method->getWeightType()) {
            case Rate::WEIGHT_TYPE_WEIGHT:
                return (float)$totals['not_free_weight'];
            case Rate::WEIGHT_TYPE_VOLUMETRIC:
                return (float)$totals['not_free_volumetric'];
            case Rate::WEIGHT_TYPE_MIN:
                return (float)min($totals['not_free_volumetric'], $totals['not_free_weight']);
            default:
                return (float)max($totals['not_free_volumetric'], $totals['not_free_weight']);
        }
    }
}
