<?php

declare(strict_types=1);

namespace Ecommerce121\AmastyShippingTableRates\Plugin\Amasty\ShippingTableRates\Helper;

use Amasty\ShippingTableRates\Helper\Data as AmastyData;

class Data
{
    const WITHOUT_SHIPPING_TYPE_VALUE = 999999;
    const WITHOUT_SHIPPING_TYPE_LABEL = 'Without shipping type assigned';

    /**
     * @param AmastyData $subject
     * @param array $result
     * @return array
     */
    public function afterGetTypes(
        AmastyData $subject,
        array $result
    ): array {
        $result[] = [
            'value'=> self::WITHOUT_SHIPPING_TYPE_VALUE,
            'label'=> self::WITHOUT_SHIPPING_TYPE_LABEL
        ];

        return $result;
    }
}