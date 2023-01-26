<?php

declare(strict_types=1);

namespace Ecommerce121\Garage\Plugin;

use Magento\Customer\Model\Session as CustomerSession;
use Ecommerce121\Garage\Model\CustomerDataContainer;
use Magento\Framework\View\LayoutInterface;

class PersonalizeCustomerId
{
    /**
     * @var CustomerDataContainer
     */
    private $customerDataContainer;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @param CustomerDataContainer $customerDataContainer
     * @param CustomerSession $customerSession
     */
    public function __construct(CustomerDataContainer $customerDataContainer, CustomerSession $customerSession)
    {
        $this->customerDataContainer = $customerDataContainer;
        $this->customerSession = $customerSession;
    }

    /**
     * @param LayoutInterface $subject
     * @return void
     */
    // @codingStandardsIgnoreLine
    public function beforeGenerateXml(LayoutInterface $subject)
    {
        $this->customerDataContainer->setCustomerId((int) $this->customerSession->getCustomerId());
    }
}
