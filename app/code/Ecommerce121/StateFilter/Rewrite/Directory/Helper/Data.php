<?php declare(strict_types=1);

namespace Ecommerce121\StateFilter\Rewrite\Directory\Helper;


use Magento\Directory\Model\CurrencyFactory;
use Magento\Directory\Model\ResourceModel\Country\Collection;
use Magento\Directory\Model\ResourceModel\Region\CollectionFactory;
use Magento\Framework\App\Cache\Type\Config;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Json\Helper\Data as JsonData;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Ecommerce121\StateFilter\Plugin\StateFilter;

class Data extends \Magento\Directory\Helper\Data
{
    /**
     * Json representation of regions data
     *
     * @var string
     */
    protected $_regionJson;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * StateFilter constructor.
     * Data modifier for admin checkout order state
     */

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param Config $configCacheType
     * @param Collection $countryCollection
     * @param CollectionFactory $regCollectionFactory
     * @param JsonData $jsonHelper
     * @param StoreManagerInterface $storeManager
     * @param CurrencyFactory $currencyFactory
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        Config $configCacheType,
        Collection $countryCollection,
        CollectionFactory $regCollectionFactory,
        JsonData $jsonHelper,
        StoreManagerInterface $storeManager,
        CurrencyFactory $currencyFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context,
            $configCacheType,
            $countryCollection,
            $regCollectionFactory,
            $jsonHelper,
            $storeManager,
            $currencyFactory,
        );
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Retrieve regions data json
     *
     * @return string
     */
    public function getRegionJson()
    {
        if (!$this->_regionJson) {
            $cacheKey = 'DIRECTORY_REGIONS_JSON_STORE' . $this->_storeManager->getStore()->getId();
            $json = $this->_configCacheType->load($cacheKey);
            if (empty($json)) {
                $regions = $this->getRegionData();
                if(isset($regions['US'])) {

                    $allowedStates = $this->scopeConfig->getValue(StateFilter::US_STATE_FILTER, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
                    $allowedUsStates = explode(",", $allowedStates);

                    $regions['US'] = array_filter($regions['US'], function ($region) use ($allowedUsStates) {
                        if (isset($region['name'])) {
                            return in_array($region['name'], $allowedUsStates);
                        }
                        return true;
                    });
                }
                $json = $this->jsonHelper->jsonEncode($regions);
                if ($json === false) {
                    $json = 'false';
                }
                $this->_configCacheType->save($json, $cacheKey);
            }
            $this->_regionJson = $json;
        }

        return $this->_regionJson;
    }
}
