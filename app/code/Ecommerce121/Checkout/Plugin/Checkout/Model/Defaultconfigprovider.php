<?php

namespace Ecommerce121\Checkout\Plugin\Checkout\Model;

use Magento\Checkout\Model\Session as CheckoutSession;


class Defaultconfigprovider

{

    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;


    /**
     * Constructor
     *
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        CheckoutSession $checkoutSession
    )
    {
        $this->checkoutSession = $checkoutSession;
    }

    public function afterGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject,
        array $result
    )
    {
        $items = $result['totalsData']['items'];
        foreach ($items as $index => $item) {
            $quoteItem = $this->checkoutSession->getQuote()->getItemById($item['item_id']);
            $result['quoteItemData'][$index]['afe_global_fitment_summary'] = $quoteItem->getProduct()->getResource()
                ->getAttributeRawValue($quoteItem->getProduct()->getId(),'afe_global_fitment_summary',0);
        }
        return $result;
    }
}
