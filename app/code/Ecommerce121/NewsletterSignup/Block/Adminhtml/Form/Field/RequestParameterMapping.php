<?php

declare(strict_types=1);

namespace Ecommerce121\NewsletterSignup\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

class RequestParameterMapping extends AbstractFieldArray
{
    /**
     * @return void
     */
    protected function _prepareToRender()
    {
        $this->addColumn('form_input_name', ['label' => __('Form input name'), 'class' => 'required-entry']);
        $this->addColumn('hubspot_parameter_name', ['label' => __('Hubspot parameter name'), 'class' => 'required-entry']);
        $this->_addAfter = false;
        $this->_addButtonLabel = __('Add');
    }
}
