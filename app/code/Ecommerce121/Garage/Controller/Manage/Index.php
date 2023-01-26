<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Controller\Manage;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index implements ActionInterface
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(PageFactory $pageFactory)
    {
        $this->pageFactory = $pageFactory;
    }

    /**
     * @inheridoc
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->pageFactory->create();
        if ($navigationBlock = $resultPage->getLayout()->getBlock('customer_account_navigation')) {
            $navigationBlock->setActive('garage/manage');
        }

        $resultPage->getConfig()->getTitle()->set(__('My Garage'));
        return $resultPage;
    }
}
