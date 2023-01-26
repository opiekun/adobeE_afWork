<?php

declare(strict_types=1);

namespace Ecommerce121\Catalog\Plugin\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Related;
use Magento\Framework\Phrase;

class RelatedPlugin
{
    /**
     * @param Related $subject
     * @param array $result
     * @return array
     */
    public function afterModifyMeta(Related $subject, array $result) : array
    {
        $values = ["related", "upsell", "crosssell"];
        foreach ($values as $value) {
            $text = $result["related"]["children"][$value]["arguments"]["data"]["config"]["label"]->getText();
            unset($result["related"]["children"][$value]["arguments"]["data"]["config"]["label"]);
            switch ($value) {
                case "related":
                    $result["related"]["children"][$value]["arguments"]["data"]["config"]["label"] = $text .
                        __(' (May also like)');
                    break;
                case "upsell":
                    $result["related"]["children"][$value]["arguments"]["data"]["config"]["label"] = $text .
                        __(' (Essentials)');
                    break;
                case "crosssell":
                    $result["related"]["children"][$value]["arguments"]["data"]["config"]["label"] = $text .
                        __(' (Add On)');
                    break;
            }
        }
        return $result;
    }
}
