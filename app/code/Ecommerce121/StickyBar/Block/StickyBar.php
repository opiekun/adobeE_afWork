<?php

declare(strict_types=1);

namespace Ecommerce121\StickyBar\Block;

use Ecommerce121\StickyBar\Api\Data\TabInterface;
use Ecommerce121\StickyBar\Api\Data\TabInterfaceFactory;
use Magento\Catalog\Block\Product\View;
use Magento\Catalog\Model\Product;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class StickyBar extends Template
{
    /**
     * @var View
     */
    private $productView;

    /**
     * @var TabInterfaceFactory
     */
    private $tabFactory;

    /**
     * @var array
     */
    private $tabs = [];

    /**
     * @param View $productView
     * @param TabInterfaceFactory $tabFactory
     * @param Context $context
     * @param array $data
     */
    public function __construct(View $productView, TabInterfaceFactory $tabFactory, Context $context, array $data = [])
    {
        parent::__construct($context, $data);

        $this->productView = $productView;
        $this->tabFactory = $tabFactory;
    }

    /**
     * @return TabInterface[]
     */
    public function getTabs(): array
    {
        usort($this->tabs, function ($a, $b) {
            return (int) $a->getSortOrder() > (int) $b->getSortOrder();
        });

        return $this->tabs;
    }

    /**
     * @param array $tabs
     * @return void
     */
    public function addTabs(array $tabs)
    {
        foreach ($tabs as $tab) {
            $this->tabs[] =  $this->tabFactory->create([
                'label' => $tab['label'] ?? '',
                'link' => $tab['link'] ?? '',
                'class' => $tab['class'] ?? '',
                'sortOrder' => (int) ($tab['sortOrder'] ?? 0),
            ]);
        }
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->productView->getProduct();
    }
}
