<?php
/**
 * Magefly_Wallmur extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Magefly
 * @package        Magefly_Wallmur
 * @copyright      Copyright (c) 2017
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Canvas edit form tab
 *
 * @category    Magefly
 * @package     Magefly_Wallmur
 * @author      Ultimate Module Creator
 */
class Magefly_Wallmur_Block_Adminhtml_Canvas_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Magefly_Wallmur_Block_Adminhtml_Canvas_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('canvas_');
        $form->setFieldNameSuffix('canvas');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'canvas_form',
            array('legend' => Mage::helper('magefly_wallmur')->__('Canvas'))
        );
        $fieldset->addType(
            'image',
            Mage::getConfig()->getBlockClassName('magefly_wallmur/adminhtml_canvas_helper_image')
        );

        $fieldset->addField(
            'canvas_code',
            'text',
            array(
                'label' => Mage::helper('magefly_wallmur')->__('Canvas Code'),
                'name'  => 'canvas_code',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'split_count',
            'text',
            array(
                'label' => Mage::helper('magefly_wallmur')->__('Split Count'),
                'name'  => 'split_count',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'width',
            'text',
            array(
                'label' => Mage::helper('magefly_wallmur')->__('Width'),
                'name'  => 'width',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'height',
            'text',
            array(
                'label' => Mage::helper('magefly_wallmur')->__('Height'),
                'name'  => 'height',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'price',
            'text',
            array(
                'label' => Mage::helper('magefly_wallmur')->__('Price'),
                'name'  => 'price',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'special_price',
            'text',
            array(
                'label' => Mage::helper('magefly_wallmur')->__('Special Price'),
                'name'  => 'special_price',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'fit_shape',
            'multiselect',
            array(
                'label' => Mage::helper('magefly_wallmur')->__('Fit Shape'),
                'name'  => 'fit_shape',
                'required'  => true,
                'class' => 'required-entry',

                'values'=> Mage::getModel('magefly_wallmur/canvas_attribute_source_fitshape')->getAllOptions(false),
           )
        );

        $fieldset->addField(
            'canvas_mockup',
            'image',
            array(
                'label' => Mage::helper('magefly_wallmur')->__('Canvas Mockup'),
                'name'  => 'canvas_mockup',

           )
        );

        $fieldset->addField(
            'canvas_thumbnail',
            'image',
            array(
                'label' => Mage::helper('magefly_wallmur')->__('Canvas Thumbnail'),
                'name'  => 'canvas_thumbnail',

           )
        );

        $fieldset->addField(
            'part_config',
            'textarea',
            array(
                'label' => Mage::helper('magefly_wallmur')->__('Part Config'),
                'name'  => 'part_config',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('magefly_wallmur')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('magefly_wallmur')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('magefly_wallmur')->__('Disabled'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_canvas')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_canvas')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getCanvasData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getCanvasData());
            Mage::getSingleton('adminhtml/session')->setCanvasData(null);
        } elseif (Mage::registry('current_canvas')) {
            $formValues = array_merge($formValues, Mage::registry('current_canvas')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
