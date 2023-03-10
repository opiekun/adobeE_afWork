<?php
/**
 * Copyright © 2015 Photoslurp. All rights reserved.
 */
namespace Photoslurp\Pswidget\Block\Adminhtml\Items\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs
{
    /**
     * Constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('photoslurp_pswidget_items_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Item'));
    }
    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }
}
