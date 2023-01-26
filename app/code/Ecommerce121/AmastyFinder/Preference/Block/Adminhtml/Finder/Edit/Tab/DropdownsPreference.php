<?php

declare(strict_types=1);

namespace Ecommerce121\AmastyFinder\Preference\Block\Adminhtml\Finder\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Amasty\Finder\Helper\Import as ImportHelper;
use Amasty\Finder\Model\Source\DisplayType;
use Amasty\Finder\Block\Adminhtml\Finder\Edit\Tab\Dropdowns;
use Magento\Framework\Data\Form;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\Config\Model\Config\Source\Yesno;

class DropdownsPreference extends Dropdowns
{
    /**
     * @var DisplayType
     */
    private $displayType;

    /**
     * @var Yesno
     */
    private $yesno;

    /**
     * @param Yesno $yesno
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param DisplayType $displayType
     * @param array $data
     */
    public function __construct(
        Yesno $yesno,
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        DisplayType $displayType,
        array $data
    ) {
        parent::__construct($context, $registry, $formFactory, $displayType, $data);
        $this->displayType = $displayType;
        $this->yesno = $yesno;
    }

    /**
     * @inheirtDoc
     */
    protected function _prepareForm()
    {
        /** @var Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('finder_dropdown_');
        $finder = $this->_coreRegistry->registry('current_amasty_finder_finder');
        $values = [];
        foreach ($finder->getDropdowns() as $dropdown) {
            $prefix = 'dropdown_' . $dropdown->getId();

            $fieldset = $form->addFieldset($prefix, ['legend' => __('Dropdown #%1', $dropdown->getPos() + 1)]);
            $fieldset->addField(
                $prefix . '_name',
                'text',
                ['name' => $prefix . '_name', 'label' => __('Name'), 'title' => __('Name'), 'required' => true]
            );
            $values[$prefix . '_name'] = $dropdown->getName();
            $fieldset->addField(
                $prefix . '_sort',
                'select',
                [
                    'name' => $prefix . '_sort',
                    'label' => __('Sort'),
                    'title' => __('Sort'),
                    'required' => true,
                    'values' => [
                        ['value' => ImportHelper::SORT_STRING_ASC, 'label' => __('alphabetically, asc')],
                        ['value' => ImportHelper::SORT_STRING_DESC, 'label' => __('alphabetically, desc')],
                        ['value' => ImportHelper::SORT_NUM_ASC, 'label' => __('numerically, asc')],
                        ['value' => ImportHelper::SORT_NUM_DESC, 'label' => __('numerically, desc')],
                    ]
                ]
            );
            $values[$prefix . '_sort'] = $dropdown->getSort();

            $fieldset->addField(
                $prefix . '_range',
                'select',
                [
                    'name' => $prefix . '_range',
                    'label' => __('Range'),
                    'title' => __('Range'),
                    'required' => true,
                    'values' => $this->yesno->toOptionArray(),
                ]
            );
            $values[$prefix . '_range'] = $dropdown->getRange();

            $fieldset->addField(
                $prefix . '_display_type',
                'select',
                [
                    'name' => $prefix . '_display_type',
                    'label' => __('Display Type'),
                    'title' => __('Display Type'),
                    'required' => true,
                    'values' => $this->displayType->toOptionArray()
                ]
            );
            $values[$prefix . '_display_type'] = $dropdown->getDisplayType();

            $fieldset->addField(
                $prefix . '_hide_in_form',
                'select',
                [
                    'name' => $prefix . '_hide_in_form',
                    'label' => __('Hide in search'),
                    'title' => __('Hide in search'),
                    'values' => $this->yesno->toOptionArray(),
                ]
            );
            $values[$prefix . '_hide_in_form'] = $dropdown->getHideInForm();

            $fieldset->addField(
                $prefix . '_required',
                'select',
                [
                    'name' => $prefix . '_required',
                    'label' => __('Required'),
                    'title' => __('Required'),
                    'values' => $this->yesno->toOptionArray(),
                ]
            );
            $values[$prefix . '_required'] = $dropdown->getRequired();
        }
        $form->setValues($values);
        $this->setForm($form);

        return $this;
    }
}
