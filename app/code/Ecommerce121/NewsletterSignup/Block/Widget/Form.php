<?php

declare(strict_types=1);

namespace Ecommerce121\NewsletterSignup\Block\Widget;

use Ecommerce121\Garage\ViewModel\Customer\Account\FormData;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;

class Form extends Template implements BlockInterface
{
    /**
     * @var FormData
     */
    private $formData;

    /**
     * @param Context $context
     * @param FormData $formData
     * @param array $data
     */
    public function __construct(Context $context, FormData $formData, array $data = [])
    {
        parent::__construct($context, $data);

        $this->formData = $formData;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return 'Ecommerce121_NewsletterSignup::widget/newsletter_signup.phtml';
    }

    /**
     * @return FormData
     */
    public function getFormData(): FormData
    {
        return $this->formData;
    }
}
