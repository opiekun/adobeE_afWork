<?php
/**
 * Copyright � 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Nextopia\Search\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;
use \Magento\Framework\App\ObjectManager;
use \Magento\Store\Model\ScopeInterface;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Helper\View as CustomerViewHelper;

/**
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class Data extends AbstractHelper
{

    /**
     * Config paths for using throughout the code
     */
    const XML_PATH_PUBLIC_CLIENT_ID = 'nextopia_search/general/public_client_id';

    const XML_PATH_SEARCHSTATUS = 'nextopia_search/general/searchstatus';

    const XML_PATH_SEARCHDEMO = 'nextopia_search/general/searchdemo';

    const XML_PATH_DEFAULT_CONTENT = 'nextopia_search/general/default_content';

    const XML_PATH_LABEL_SEARCH_RESULT_PAGE = 'nextopia_search/general/label_search_result_page';

    /**
     * @return bool
     */
    public function isEnabled($storeId = null) {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_SEARCHSTATUS, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @return bool
     */
    public function isDemo($storeId = null) {
        return (bool)$this->scopeConfig->getValue(self::XML_PATH_SEARCHDEMO, ScopeInterface::SCOPE_STORE, $storeId);
    }
    
    
    public function getNxtId($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_PUBLIC_CLIENT_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getLoadingContent($storeId = null)
    {
        return $this->scopeConfig->getValue(self::XML_PATH_DEFAULT_CONTENT, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * This is only to determine if the search should be visible on our page or not.
     */
    public function showInNxtSearchPage($storeId = null)
    {
        return ($this->isEnabled($storeId) || $this->isDemo($storeId));
    }

    /**
     * Determines if the search box takeover happens everywhere or not.
     */
    public function showEverywhere($storeId = null)
    {
        return $this->isEnabled($storeId);
    }

    public function getResultUrl($storeId = null)
    {
        return $this->_urlBuilder->getUrl("nsearch");
    }

    public function getGroupCode()
    {
        $om = ObjectManager::getInstance();
        $customerSession = $om->get('Magento\Customer\Model\Session');
        $groupRepository = $om->create('\Magento\Customer\Api\GroupRepositoryInterface');
        $group = $groupRepository->getById($customerSession->getCustomer()->getGroupId());

        return empty($group) ? "Unknown" : $group->getCode();

    }

    public function getFormKey() {
        /** @var \Magento\Framework\Data\Form\FormKey $formKey */
        $om = $om = ObjectManager::getInstance();
        $formKey = $om->get('Magento\Framework\Data\Form\FormKey');
        return $formKey->getFormKey();
    }
    
    public function getLabelSearchResultPage($storeId = null) {

        return $this->scopeConfig->getValue(self::XML_PATH_LABEL_SEARCH_RESULT_PAGE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        CustomerViewHelper $customerViewHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\ObjectManagerInterface $objectManager
        ) {
        
        parent::__construct($context);
        
        $this->_customerSession = $customerSession;
        $this->_customerViewHelper = $customerViewHelper;
        $this->_storeManager = $storeManager;
        $this->_objectManager = $objectManager;
    }

    public function getCurrentStoreId() {
        
        try {
            return $this->_storeManager->getStore()->getStoreId(); 
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function isClientOneLineCodeAvailable($client_id) {

        $url = "cdn.nextopia.net/nxt-app/$client_id.js";
        $handle = curl_init($url);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        curl_exec($handle);
            
        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
        
        return ($httpCode === 200)? true: false;
   }
}