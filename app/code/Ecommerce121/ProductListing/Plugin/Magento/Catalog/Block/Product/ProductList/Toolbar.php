<?php

declare(strict_types=1);

namespace Ecommerce121\ProductListing\Plugin\Magento\Catalog\Block\Product\ProductList;

use Closure;
use Magento\Catalog\Block\Product\ProductList\Toolbar as Productdata;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product\ProductList\Toolbar as ToolbarModel;
use Magento\Catalog\Model\Product\ProductList\ToolbarMemorizer;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Registry;

class Toolbar
{
    /**
     * @var Http
     */
    private Http $request;

    /**
     * @var ToolbarMemorizer|mixed|null
     */
    private ?ToolbarMemorizer $toolbarMemorizer;

    /**
     * @var Context|mixed|null
     */
    private ?Context $httpContext;

    /**
     * @var Registry|mixed|null
     */
    private ?Registry $registry;

    /**
     * @param Http $request
     * @param ToolbarMemorizer|null $toolbarMemorizer
     * @param Context|null $httpContext
     * @param Registry|null $registry
     */
    public function __construct(
        Http $request,
        ToolbarMemorizer $toolbarMemorizer = null,
        Context $httpContext = null,
        Registry $registry = null
    ) {
        $this->request = $request;
        $this->toolbarMemorizer = $toolbarMemorizer ?: ObjectManager::getInstance()->get(
            ToolbarMemorizer::class
        );
        $this->httpContext = $httpContext ?: ObjectManager::getInstance()->get(
            Context::class
        );
        $this->registry = $registry ?: ObjectManager::getInstance()->get(
            Registry::class
        );
    }

    /**
     * @param Productdata $subject
     * @param $result
     * @return string
     */
    public function afterGetCurrentOrder(
        Productdata $subject,
        $result
    ): string
    {
        $order = $this->_getData('_current_grid_order');
        if ($order) {
            return $order;
        }

        $orders = $subject->getAvailableOrders();
        if ($this->getFirstLevelCategory() && $this->getFirstLevelCategory() == 'products') {
            $defaultOrder = 'created_at';
        } else {
            $defaultOrder = 'afe_product_ranking';
        }

        if (!isset($orders[$defaultOrder])) {
            $keys = array_keys($orders);
            $defaultOrder = $keys[0];
        }

        $order = $this->toolbarMemorizer->getOrder();
        if (!$order || !isset($orders[$order])) {
            $order = $defaultOrder;
        }

        if ($this->toolbarMemorizer->isMemorizingAllowed()) {
            $this->httpContext->setValue(ToolbarModel::ORDER_PARAM_NAME, $order, $defaultOrder);
        }

        $subject->setData('_current_grid_order', $order);

        return $order;
    }


    /**
     * @param string $key
     * @return  mixed
     */
    protected function _getData(string $key)
    {
        if (isset($this->_data[$key])) {
            return $this->_data[$key];
        }

        return null;
    }

    /**
     * @return mixed|null
     */
    private function getCurrentCategory()
    {
        return $this->registry->registry('current_category');
    }

    /**
     * @return null
     */
    public function getFirstLevelCategory(): ?string
    {

        if ($this->getCurrentCategory()) {
            if ($this->getCurrentCategory()->getParentCategories()) {
                /**
                 * @var $parent Category
                 */
                foreach ($this->getCurrentCategory()->getParentCategories() as $parent) {
                    if ($parent->getLevel() == 2) {
                        return $parent->getUrlKey();
                    }
                }
            }
        }

        return null;
    }
}
