<?php

declare(strict_types=1);

namespace Ecommerce121\ExtendedBasket\Plugin;

use Bss\AjaxCart\Helper\Data;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Ecommerce121\Basket\Model\Product\Type;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class ManageAddToCartAjax
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param RequestInterface $request
     */
    public function __construct(ProductRepositoryInterface $productRepository, RequestInterface $request)
    {
        $this->productRepository = $productRepository;
        $this->request = $request;
    }

    /**
     * @param Data $subject
     * @param bool $result
     * @return bool
     * @throws NoSuchEntityException
     */
    public function afterIsEnabled(Data $subject, bool $result)
    {
        $productId = (int) $this->request->getParam('id');

        if (!$productId) {
            return $result;
        }

        try {
            $product = $this->productRepository->getById($productId);
            if ($product->getTypeId() === Type::TYPE_CODE) {
                return false;
            }
        } catch (LocalizedException $e) {
            return false;
        }

        return $result;
    }
}
