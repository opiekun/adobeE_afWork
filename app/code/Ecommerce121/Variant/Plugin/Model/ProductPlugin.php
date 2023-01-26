<?php

declare(strict_types=1);

namespace Ecommerce121\Variant\Plugin\Model;

use Magento\Catalog\Model\Product;

class ProductPlugin {

    /**
     * Remove variant images from main image gallery
     *
     * @param Product $subject
     * @param $result
     * @return mixed
     */
    public function afterGetMediaGalleryImages(Product $subject, $result)
    {
        if ($items = $result->getItems()) {
            foreach ($items as $key=>$item) {
                $file = $item->getData('file');
                if ($file == $subject->getVariant()) {
                    $result->removeItemByKey($key);
                }
            }
        }

        return $result;
    }
}
