<?php

declare(strict_types=1);

namespace Ecommerce121\SearchSpring\Model\Store\Config\Source;

use Ecommerce121\SearchSpring\Model\Store\Config;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @inheridoc
     */
    public function toOptionArray(): array
    {
        $result = [];
        $result[] = [
            'value' => Config::CATALOGSEARCH_CONFIG_VALUE,
            'label' => Config::CATALOGSEARCH_CONFIG_LABEL
        ];

        return $result;
    }
}
