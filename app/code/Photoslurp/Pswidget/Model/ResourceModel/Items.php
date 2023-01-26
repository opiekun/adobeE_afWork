<?php
/**
 * Copyright © 2015 Photoslurp. All rights reserved.
 */

namespace Photoslurp\Pswidget\Model\ResourceModel;

class Items extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('photoslurp_pswidget_items', 'id');
    }
}
