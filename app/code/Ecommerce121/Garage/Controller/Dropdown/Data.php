<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Controller\Dropdown;

use Ecommerce121\Garage\Model\ResourceModel\DropdownData;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

class Data extends Action
{
    /**
     * @var DropdownData
     */
    private $dropdownData;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        DropdownData $dropdownData
    ) {
        $this->resultJsonFactory = $jsonFactory;
        $this->dropdownData = $dropdownData;
        parent::__construct($context);
    }

    public function execute()
    {
        $dropdownId = $this->getRequest()->getParam('dropdown_id');
        $dropdownData = $this->getDropdownData($dropdownId);

        return $this->resultJsonFactory->create()->setData($dropdownData);
    }

    /**
     * @param $dropdownId
     * @return array
     */
    public function getDropdownData($dropdownId): array
    {
        return $this->dropdownData->getDropdownData($dropdownId);
    }
}
