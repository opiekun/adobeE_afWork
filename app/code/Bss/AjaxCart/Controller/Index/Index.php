<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_AjaxCart
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\AjaxCart\Controller\Index;

use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 *
 * @package Bss\AjaxCart\Controller\Index
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * Form key validator
     *
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $formKeyValidator;

    /**
     * Customer cart
     *
     * @var CustomerCart
     */
    protected $cart;

    /**
     * Resolver.
     *
     * @var \Magento\Framework\Locale\ResolverInterface
     */
    protected $resolverInterface;

    /**
     * Escaper.
     *
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * Logger.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $loggerInterface;

    /**
     * Result page factory.
     *
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Product repository.
     *
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Store manager.
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Ajax cart helper.
     *
     * @var \Bss\AjaxCart\Helper\Data
     */
    protected $ajaxHelper;

    /**
     * Localized to normalized.
     *
     * @var \Bss\AjaxCart\Filter\LocalizedToNormalized
     */
    protected $localizedToNormalized;

    /**
     * Data object factory.
     *
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * Url builder.
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlInterface;

    /**
     * @var bool
     */
    protected $relatedAdded = false;
    /**
     * @var \Magento\Wishlist\Model\ItemFactory
     */
    private $itemFactory;
    /**
     * @var \Magento\Wishlist\Model\Item\OptionFactory
     */
    private $optionFactory;
    /**
     * @var \Magento\Catalog\Helper\Product
     */
    private $productHelper;
    /**
     * @var \Magento\Wishlist\Model\Wishlist
     */
    protected $wishlist;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param CustomerCart $cart
     * @param \Magento\Framework\Locale\ResolverInterface $resolverInterface
     * @param \Magento\Framework\Escaper $escaper
     * @param \Psr\Log\LoggerInterface $loggerInterface
     * @param PageFactory $resultPageFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Bss\AjaxCart\Helper\Data $ajaxHelper
     * @param \Bss\AjaxCart\Filter\LocalizedToNormalized $localizedToNormalized
     * @param \Magento\Framework\DataObjectFactory $dataObjectFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Wishlist\Model\ItemFactory $itemFactory
     * @param \Magento\Wishlist\Model\Item\OptionFactory $optionFactory
     * @param \Magento\Catalog\Helper\Product $productHelper
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        CustomerCart $cart,
        \Magento\Framework\Locale\ResolverInterface $resolverInterface,
        \Magento\Framework\Escaper $escaper,
        \Psr\Log\LoggerInterface $loggerInterface,
        PageFactory $resultPageFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Bss\AjaxCart\Helper\Data $ajaxHelper,
        \Bss\AjaxCart\Filter\LocalizedToNormalized $localizedToNormalized,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        \Magento\Framework\Registry $registry,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Wishlist\Model\ItemFactory $itemFactory,
        \Magento\Wishlist\Model\Item\OptionFactory $optionFactory,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Wishlist\Model\Wishlist $wishlist
    ) {
        parent::__construct($context);
        $this->formKeyValidator = $formKeyValidator;
        $this->cart = $cart;
        $this->resolverInterface = $resolverInterface;
        $this->escaper = $escaper;
        $this->loggerInterface = $loggerInterface;
        $this->resultPageFactory = $resultPageFactory;
        $this->productRepository = $productRepository;
        $this->storeManager = $storeManager;
        $this->ajaxHelper = $ajaxHelper;
        $this->localizedToNormalized = $localizedToNormalized;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->registry = $registry;
        $this->customerSession = $customerSession;
        $this->urlInterface = $context->getUrl();
        $this->itemFactory = $itemFactory;
        $this->optionFactory = $optionFactory;
        $this->productHelper = $productHelper;
        $this->wishlist = $wishlist;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        if (!$this->ajaxHelper->isEnabled()) {
            return parent::execute();
        }
        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('/');
        }

        $additionalInfo = [];
        $params = $this->getRequest()->getParams();
        $specifyOptions = $this->getRequest()->getParam('specifyOptions');
        if ($specifyOptions) {
            $product = $this->initProduct();
            $notice = $product->getTypeInstance()->getSpecifyOptionMessage();
            $this->messageManager->addNoticeMessage(
                $this->escaper->escapeHtml($notice)
            );
            $result = [];
            $result['error'] = true;
            $result['view'] = true;
            $result['message'] = false;
            $result['url'] = $this->escaper->escapeUrl(
                $this->urlInterface->getUrl('ajaxcart/index/view', ['id' => $params['id']])
            );
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($result);
            return $resultJson;
        }
        try {
            $params = $this->setQtyParams($params);
            $product = $this->initProduct();
            $additionalInfo['product_id'] = $product->getId();

            if (!$product) {
                return $this->resultRedirectFactory->create()->setPath('/');
            }
            $data = [
                'status' => true,
                'added' => false,
                'messages' => []
            ];
            $result = $this->dataObjectFactory->create()->setData($data);
            $this->_eventManager->dispatch(
                'bss_ajaxcart_add_before',
                ['product' => $product, 'request' => $this->getRequest(), 'result' => $result]
            );
            if (!$result->getData('status') && empty($messages)) {
                return $this->resultRedirectFactory->create()->setPath('/');
            }
            $messages = $result->getData('messages');

            if (!empty($messages)) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    $messages[0]['message']
                );
            }
            if (isset($params['cmsName']) && $params['cmsName'] == 'wishlist_index_index') {
                $item = $this->itemFactory->create()->load($params['item']);
                $item->setQty($params['qty']);
                $additionalInfo['wishlist_item'] = $params['item'];

                $options = $this->optionFactory->create()->getCollection()->addItemFilter($params['item']);
                $item->setOptions($options->getOptionsByItem($params['item']));
                $buyRequest = $this->productHelper->addParamsToBuyRequest(
                    $this->getRequest()->getParams(),
                    ['current_config' => $item->getBuyRequest()]
                );
                $item->mergeBuyRequest($buyRequest);
                $item->addToCart($this->cart, true);
                $this->cart->save()->getQuote()->collectTotals();
            } else {
                $this->addProduct($result, $product, $params);
                $related = $this->getRequest()->getParam('related_product');
                $messages = $result->getData('messages');
                foreach ($messages as $message) {
                    $this->addResultMessage($message);
                }
                $this->addProductsById($related);
                $this->cart->save();

                // Quick view was showed in wishlist page
                // when try add to cart a product with required options (like configurable products, custom options...)
                // params have no 'cmsName' = 'wishlist_index_index'
                // so, we do check if params have 'added_from_wishlist' = 1
                // then remove this item from wishlist
                if (isset($params['added_from_wishlist']) &&
                    $params['added_from_wishlist'] == 1) {
                    $customerId = $this->customerSession->getCustomer()->getId();
                    if ($customerId) {
                        $currentCustomerWishlist = $this->wishlist->loadByCustomerId($customerId);
                        $wishlistItems = $currentCustomerWishlist->getItemCollection();

                        foreach ($wishlistItems as $wishlistItem) {
                            if ($wishlistItem->getProductId() == $product->getId()) {
                                $additionalInfo['wishlist_item'] = $wishlistItem->getId();
                                $wishlistItem->delete();
                            }
                        }
                    }
                }
            }

            $this->_eventManager->dispatch(
                'checkout_cart_add_product_complete',
                ['product' => $product, 'request' => $this->getRequest(), 'response' => $this->getResponse()]
            );
            $resultItem = $product->getTypeId() == \Magento\GroupedProduct\Model\Product\Type\Grouped::TYPE_CODE ?
                $this->dataObjectFactory->create()->setProduct($product)
                : $this->registry->registry('last_added_quote_item');
            return $this->returnResult($resultItem, $this->relatedAdded, $additionalInfo);
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addNoticeMessage(
                $this->escaper->escapeHtml($e->getMessage())
            );
            $result = [];
            $result['error'] = true;
            $result['view'] = true;
            $result['message'] = $e->getMessage();
            $result['url'] = $this->escaper->escapeUrl(
                $this->urlInterface->getUrl('ajaxcart/index/view', ['id' => $params['id']])
            );
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($result);
            return $resultJson;
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage(
                $e,
                __('We can\'t add this item to your shopping cart right now.')
            );
            $this->loggerInterface->critical($e);
            $result = [];
            $result['error'] = true;
            $result['message'] = $e->getMessage();
            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($result);
            return $resultJson;
        }
    }

    /**
     * Init requested product.
     *
     * @return bool|\Magento\Catalog\Api\Data\ProductInterface
     */
    protected function initProduct()
    {
        $productId = (int)$this->getRequest()->getParam('product');

        if (!$productId) {
            $productId = (int)$this->getRequest()->getParam('id');
        }

        if ($productId) {
            try {
                $storeId = $this->storeManager->getStore()->getId();
                return $this->productRepository->getById($productId, false, $storeId);
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                return false;
            }
        }

        return false;
    }

    /**
     * Return add to cart result.
     *
     * @param \Magento\Quote\Model\Quote\Item $resultItem
     * @param boolean $relatedAdded
     * @param array $additionalInfo
     * @return \Magento\Framework\Controller\ResultInterface
     */
    protected function returnResult($resultItem, $relatedAdded, $additionalInfo = [])
    {
        if (!$this->cart->getQuote()->getHasError()) {
            $result = [];

            $popupTemplate = 'Bss_AjaxCart::popup.phtml';

            $params = $this->getRequest()->getParams();
            $productId = isset($params['id']) ? $params['id'] : $resultItem->getProductId();

            $resultPage = $this->resultPageFactory->create();
            $popupBlock = $resultPage->getLayout()
                ->createBlock(\Bss\AjaxCart\Block\Ajax\Template::class)
                ->setTemplate($popupTemplate)
                ->setItem($resultItem)
                ->setRelatedAdded($relatedAdded);

            if ($this->ajaxHelper->isShowSuggestBlock()) {
                $suggestTemplate = 'Bss_AjaxCart::popup/suggest.phtml';
                $suggestBlock = $resultPage->getLayout()
                    ->createBlock(\Bss\AjaxCart\Block\Popup\Suggest::class)
                    ->setTemplate($suggestTemplate)
                    ->setProductId($productId);

                $popupAjaxTemplate = 'Bss_AjaxCart::popup/ajax.phtml';
                $popupAjaxBlock = $resultPage->getLayout()
                    ->createBlock(\Bss\AjaxCart\Block\Ajax\Template::class)
                    ->setTemplate($popupAjaxTemplate);

                $suggestBlock->setChild('ajaxcart.popup.ajax.suggest', $popupAjaxBlock);
                $popupBlock->setChild('ajaxcart.popup.suggest', $suggestBlock);
            }

            $html = $popupBlock->toHtml();

            $message = __(
                'You added %1 to your shopping cart.',
                $resultItem->getName()
            );
            $this->messageManager->addSuccessMessage($message);

            $result['popup'] = $html;
            unset($additionalInfo['form_key']);
            $result = array_merge($result, $additionalInfo);

            $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $resultJson->setData($result);
            return $resultJson;
        }
    }

    /**
     * Add message from bss_ajaxcart_add_before result
     *
     * @param array $message
     * @return void
     */
    protected function addResultMessage($message)
    {
        if (isset($message['type'])) {
            switch ($message['type']) {
                case "notice":
                    $this->messageManager->addNoticeMessage(
                        $this->escaper->escapeHtml($message['message'])
                    );
                    break;
                case "error":
                    $this->messageManager->addErrorMessage(
                        $this->escaper->escapeHtml($message['message'])
                    );
                    break;
                case "success":
                    $this->messageManager->addSuccessMessage(
                        $this->escaper->escapeHtml($message['message'])
                    );
                    break;
                default:
                    $this->messageManager->addNoticeMessage(
                        $this->escaper->escapeHtml($message['message'])
                    );
            }
        }
    }

    /**
     * @param $params
     * @return mixed
     * @throws \Zend_Locale_Exception
     */
    protected function setQtyParams($params)
    {
        if (array_key_exists('qty', $params)) {
            // Mixed wish list check qty
            $qty = $val = $params['qty'];
            $id = $params['id'];
            $filter = $this->localizedToNormalized;
            if (is_array($qty) && isset($qty[$id])) {
                $val = $qty[$id];
                $valAfter = $filter->filter($val);
                $qty[$id] = $valAfter;
                $params['qty'] = $qty;
            } elseif (is_string($qty) || is_int($qty)) {
                $qty = $filter->filter($val);
                $params['qty'] = $qty;
            } else {
                $params['qty'] = $qty;
            }
        }
        return $params;
    }

    /**
     * @param $result
     * @param $product
     * @param $params
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function addProduct($result, $product, $params)
    {
        if (!$result->getData('added')) {
            $this->cart->addProduct($product, $params);
        }
    }

    /**
     * @param $related
     */
    protected function addProductsById($related)
    {
        if (!empty($related)) {
            $this->relatedAdded = true;
            $this->cart->addProductsByIds(explode(',', $related));
        }
    }
}
