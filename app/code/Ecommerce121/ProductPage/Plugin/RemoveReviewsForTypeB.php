<?php

declare(strict_types=1);

namespace Ecommerce121\ProductPage\Plugin;

use Magento\Catalog\Block\Product\View\Details;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Layout;
use Magento\Catalog\Api\ProductRepositoryInterface;

class RemoveReviewsForTypeB
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    /**
     * @param Details $reviewBlock
     * @return void
     */
    public function beforeToHtml(Details $reviewBlock)
    {
        try {
            /** @var Layout $layout */
            $layout = $reviewBlock->getLayout();
            if (!$layout->getBlock('reviews.tab')) {
                return;
            }

            $product = $this->productRepository->getById($layout->getBlock('reviews.tab')->getProductId());
            if ($product->getPageLayout() === 'product-type-b') {
                $layout->unsetElement('reviews.tab');
            }
        } catch (LocalizedException $e) {
            return;
        }
    }
}
