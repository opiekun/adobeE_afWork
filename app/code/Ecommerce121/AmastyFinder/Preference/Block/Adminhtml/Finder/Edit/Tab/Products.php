<?php

declare(strict_types=1);

namespace Ecommerce121\AmastyFinder\Preference\Block\Adminhtml\Finder\Edit\Tab;

class Products extends \Amasty\Finder\Block\Adminhtml\Finder\Edit\Tab\Products
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'finder';
        $this->_headerText = __('Products');
        $this->_addButtonLabel = __('Add New Product');
        parent::_construct();
        $this->addButton(
            'add_new',
            [
                'label' => $this->getAddButtonLabel(),
                'onclick' => 'setLocation(\'' . $this->getCreateUrl() . '\')',
                'class' => 'add primary'
            ],
            0,
            0,
            $this->getNameInLayout()
        );
        $this->removeButton('add');
        $this->removeButton('remove_all');
    }
}
