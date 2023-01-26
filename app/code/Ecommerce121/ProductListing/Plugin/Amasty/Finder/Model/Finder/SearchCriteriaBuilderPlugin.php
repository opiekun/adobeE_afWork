<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\Plugin\Amasty\Finder\Model\Finder;

use Amasty\Finder\Helper\Config;
use Amasty\Finder\Model\Session;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\Request\Http;

class SearchCriteriaBuilderPlugin
{
    /**
     * @var Session
     */
    private $finderSession;

    /**
     * @var Config
     */
    private $configHelper;

    /**
     * @var Http
     */
    private Http $request;

    /**
     * @param Session $finderSession
     * @param Config $configHelper
     * @param Http $request
     */
    public function __construct(
        Session $finderSession,
        Config $configHelper,
        Http $request
    )
    {
        $this->finderSession = $finderSession;
        $this->configHelper = $configHelper;
        $this->request = $request;
    }

    /**
     * @param $subject
     * @param $result
     * @param Collection $collection
     * @param $filter
     * @param $filterCondition
     * @return $this
     */
    public function afterAddCollectionFilter(
        $subject,
        $result,
        Collection $collection,
        $filter,
        $filterCondition
    )
    {
        $finderId = $this->getFinderId();
        $sessionData = $this->finderSession->getFinderData($finderId);
        $sessionData[$filter] = $filterCondition;
        $this->finderSession->setFinderData($finderId, $sessionData);

        $defaultSortOrderParam = 'afe_product_ranking';
        $sortOrderParam = $this->request->getParam('product_list_order', $defaultSortOrderParam);
        $sortDirectionParam = $this->request->getParam('product_list_dir', 'ASC');

        $collection->setOrder($sortOrderParam, $sortDirectionParam);

        return $result;
    }

    /**
     * @return int
     */
    private function getFinderId(): int
    {
        $finderId = $this->configHelper->getConfigValue('advanced/compatible_finder');

        if (!$finderId) {
            $activeFinders = $this->finderSession->getAllFindersData();
            if ($activeFinders) {
                $finderIds = array_keys($activeFinders);
                $finderId = array_shift($finderIds);
            }
        }

        return (int) $finderId;
    }

}
