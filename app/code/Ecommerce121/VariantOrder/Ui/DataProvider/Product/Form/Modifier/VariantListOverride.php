<?php declare(strict_types=1);

namespace Ecommerce121\VariantOrder\Ui\DataProvider\Product\Form\Modifier;

use Ecommerce121\ProductVariation\Ui\DataProvider\Product\Form\Modifier\VariantList;

class VariantListOverride extends VariantList
{
    // This is needed because the parent's class has it private and it will complain when we inherit it
    protected const DATA_SCOPE = 'variant_list';

    public function modifyMeta(array $meta): array
    {
        if (!isset($meta['ecommerce121-product-variants'])) {
            return $meta;
        }

        $meta = parent::modifyMeta($meta);
        $meta['ecommerce121-product-variants']['children']['variant_order']['arguments']['data']['config']['dataScope']
            = 'data.product.variant_order';
        return $meta;
    }

}
