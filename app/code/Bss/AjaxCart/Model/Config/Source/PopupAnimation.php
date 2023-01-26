<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * @category   BSS
 * @package    Bss_AjaxCart
 * @author     Extension Team
 * @copyright  Copyright (c) 2017-2018 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\AjaxCart\Model\Config\Source;

/**
 * Class PopupAnimation
 *
 * @package Bss\AjaxCart\Model\Config\Source
 */
class PopupAnimation implements \Magento\Framework\Option\ArrayInterface
{
    const POPUP_ANIMATION_NONE = 0;
    const POPUP_ZOOM_OUT = 1;
    const POPUP_3D_UNFOLD = 2;
    const POPUP_MOVE_FROM_TOP = 3;
    const POPUP_HORIZONTAL_MOVE = 4;
    const POPUP_ZOOM = 5;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return  [
            ['value' => self::POPUP_ANIMATION_NONE, 'label' => __('None')],
            ['value' => self::POPUP_ZOOM_OUT, 'label' => __('Zoom out')],
            ['value' => self::POPUP_3D_UNFOLD, 'label' => __('3D Unfold')],
            ['value' => self::POPUP_MOVE_FROM_TOP, 'label' => __('Move from Top')],
            ['value' => self::POPUP_HORIZONTAL_MOVE, 'label' => __('Horizontal Move')],
            ['value' => self::POPUP_ZOOM, 'label' => __('Zoom')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::POPUP_ANIMATION_NONE => __('None'),
            self::POPUP_ZOOM_OUT => __('Zoom out'),
            self::POPUP_3D_UNFOLD => __('3D Unfold'),
            self::POPUP_MOVE_FROM_TOP => __('Move from Top'),
            self::POPUP_HORIZONTAL_MOVE => __('Horizontal Move'),
            self::POPUP_ZOOM => __('Zoom')
        ];
    }
}
