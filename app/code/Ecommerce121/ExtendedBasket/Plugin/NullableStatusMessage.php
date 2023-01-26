<?php

declare(strict_types=1);

namespace Ecommerce121\ExtendedBasket\Plugin;

use Amasty\Stockstatus\Model\Stockstatus\Information;
use Magento\Framework\Exception\LocalizedException;

class NullableStatusMessage
{
    /**
     * @param Information $subject
     * @param callable $proceed
     * @return string
     */
    public function aroundGetStatusMessage(Information $subject, callable $proceed)
    {
        try {
            return $proceed();
        } catch (\Throwable $exception) {
            return '';
        }
    }
}
