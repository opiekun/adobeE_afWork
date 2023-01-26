<?php

declare(strict_types=1);

namespace Ecommerce121\AmastyShippingTableRates\Plugin\Amasty\ShippingTableRates\Model\Rate;

use Amasty\ShippingTableRates\Model\Rate\ItemValidator as AmastyItemValidator;
use Ecommerce121\AmastyShippingTableRates\Plugin\Amasty\ShippingTableRates\Helper\Data;

class ItemValidator
{
    /**
     * @param AmastyItemValidator $subject
     * @param bool $result
     * @param $item
     * @param $shippingType
     * @return bool
     */
    public function afterIsShippingTypeValid(
        AmastyItemValidator $subject,
        bool $result,
        $item,
        $shippingType
    ): bool {
        return ($shippingType == Data::WITHOUT_SHIPPING_TYPE_VALUE) ? true : $result;
    }
}