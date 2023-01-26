<?php

declare(strict_types=1);

namespace Ecommerce121\AmastyFinder\Observer;

use Magento\Framework\App\Request\Http;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Layout;

class RemoveDefaultFinder implements ObserverInterface
{
    /**
     * @var Http
     */
    private $request;

    /**
     * @param Http $request
     */
    public function __construct(
        Http $request
    ) {
        $this->request = $request;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if ($this->request->getFullActionName() == 'catalog_category_view') {
            /** @var Layout $layout */
            $layout = $observer->getLayout();
            $blocks = $layout->getAllBlocks();

            foreach ($blocks as $key => $block) {
                /** @var Template $block */
                if ($block->getTemplate() == 'amfinder.phtml') {
                    $layout->unsetElement($key);
                }
            }
        }
    }
}