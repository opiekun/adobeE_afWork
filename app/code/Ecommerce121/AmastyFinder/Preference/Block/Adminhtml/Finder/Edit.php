<?php

declare(strict_types=1);

namespace Ecommerce121\AmastyFinder\Preference\Block\Adminhtml\Finder;

class Edit extends \Amasty\Finder\Block\Adminhtml\Finder\Edit
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_finder';
        $this->_blockGroup = 'Amasty_Finder';

        parent::_construct();

        $this->buttonList->add(
            'save_and_continue_edit',
            [
                'class' => 'save',
                'label' => __('Save and Continue Edit'),
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
                ]
            ],
            10
        );

        $this->buttonList->remove('delete');
    }
}
