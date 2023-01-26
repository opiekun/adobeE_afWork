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
namespace Bss\AjaxCart\Plugin\Wishlist\SideBar;

use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

/**
 * Class AddToCartParams
 * @package Bss\AjaxCart\Plugin\Wishlist\SideBar
 */
class AddToCartParams
{

    /**
     * @var JsonSerializer
     */
    protected $jsonSerializer;

    /**
     * AddToCartParams constructor.
     * @param JsonSerializer $jsonSerializer
     */
    public function __construct(
        JsonSerializer $jsonSerializer
    ) {
        $this->jsonSerializer = $jsonSerializer;
    }

    /**
     * @param \Magento\Wishlist\Helper\Data $helperSubject
     * @param $result
     * @return bool|false|string
     */
    public function afterGetAddToCartParams(
        \Magento\Wishlist\Helper\Data $helperSubject,
        $result
    ) {
        $resultArray = $this->jsonSerializer->unserialize($result);
        if ($resultArray['data']) {
            $resultArray['data']['added_from_wishlist'] = 1;
        }
        return $this->jsonSerializer->serialize($resultArray);
    }
}