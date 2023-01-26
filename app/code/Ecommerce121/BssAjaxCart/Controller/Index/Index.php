<?php

declare(strict_types=1);

namespace Ecommerce121\BssAjaxCart\Controller\Index;

use Bss\AjaxCart\Block\Ajax\Template;
use Bss\AjaxCart\Block\Popup\Suggest;
use Bss\AjaxCart\Controller\Index\Index as BssAjaxCartIndex;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Quote\Model\Quote\Item;

class Index extends BssAjaxCartIndex
{
    /**
     * @param Item $resultItem
     * @param boolean $relatedAdded
     * @param array $additionalInfo
     * @return ResultInterface
     */
    protected function returnResult(
        $resultItem,
        $relatedAdded,
        $additionalInfo = []
    ): ?ResultInterface
    {
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        if (!$this->cart->getQuote()->getHasError()) {
            $result = [];

            $popupTemplate = 'Bss_AjaxCart::popup.phtml';

            $params = $this->getRequest()->getParams();
            $productId = $params['id'] ?? $resultItem->getProductId();

            $resultPage = $this->resultPageFactory->create();
            $popupBlock = $resultPage->getLayout()
                ->createBlock(Template::class)
                ->setTemplate($popupTemplate)
                ->setItem($resultItem)
                ->setRelatedAdded($relatedAdded);

            if ($this->ajaxHelper->isShowSuggestBlock()) {
                $suggestTemplate = 'Bss_AjaxCart::popup/suggest.phtml';
                $suggestBlock = $resultPage->getLayout()
                    ->createBlock(Suggest::class)
                    ->setTemplate($suggestTemplate)
                    ->setProductId($productId);

                $popupAjaxTemplate = 'Bss_AjaxCart::popup/ajax.phtml';
                $popupAjaxBlock = $resultPage->getLayout()
                    ->createBlock(Template::class)
                    ->setTemplate($popupAjaxTemplate);

                $suggestBlock->setChild('ajaxcart.popup.ajax.suggest', $popupAjaxBlock);
                $popupBlock->setChild('ajaxcart.popup.suggest', $suggestBlock);
            }

            $html = $popupBlock->toHtml();

            $result['popup'] = $html;
            unset($additionalInfo['form_key']);

            $result = array_merge($result, $additionalInfo);
            $resultJson->setData($result);

            return $resultJson;
        }

        return $resultJson;
    }
}
