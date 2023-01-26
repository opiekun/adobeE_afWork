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
namespace Bss\AjaxCart\Model\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class ProductAddToCartAfter implements ObserverInterface
{
    /**
     * Ajax cart helper.
     *
     * @var \Bss\AjaxCart\Helper\Data
     */
    protected $helper;

    /**
     * Core registry.
     *
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * Initialize dependencies.
     *
     * @param \Bss\AjaxCart\Helper\Data $helper
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        \Bss\AjaxCart\Helper\Data $helper,
        \Magento\Framework\Registry $registry
    ) {
        $this->helper = $helper;
        $this->registry = $registry;
    }

    /**
     * Check is show additional data in quick view.
     *
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        if ($this->helper->isEnabled()) {
            $resultItem = $observer->getQuoteItem();
            $this->registry->unregister('last_added_quote_item');
            $this->registry->register('last_added_quote_item', $resultItem);
        }
    }
}
