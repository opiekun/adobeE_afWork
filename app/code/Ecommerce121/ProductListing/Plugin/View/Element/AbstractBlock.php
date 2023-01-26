<?php declare(strict_types=1);

namespace Ecommerce121\ProductListing\Plugin\View\Element;

use Ecommerce121\ProductListing\ViewModel\Menu;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\AbstractBlock as MagentoAbstractBlock;
use Magento\Framework\View\LayoutInterface;

class AbstractBlock
{

    /**
     * @var Menu
     */
    private Menu $viewModel;

    /**
     * @param UrlInterface $url
     * @param Menu $viewModel
     */
    public function __construct(
        Menu $viewModel
    )
    {
        $this->viewModel = $viewModel;
    }

    /**
     * @param MagentoAbstractBlock $subject
     * @param $result
     * @param LayoutInterface $layout
     * @return mixed
     */
    public function afterSetLayout(
        MagentoAbstractBlock $subject,
        $result,
        LayoutInterface $layout
    )
    {
        $title = str_replace('/', '|', $this->viewModel->obtainTitle());
        if ($title && strpos($title, "Default Category") === false  && $result->getData('current_category')) {
            $result->getData('current_category')->setName(
                $title
            );
        }

        return $result;
    }

}
