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
 * Canvas admin edit form
 *
 * @category    Magefly
 * @package     Magefly_Wallmur
 * @author      Ultimate Module Creator
 */
class Magefly_Wallmur_Block_Adminhtml_Canvas_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'magefly_wallmur';
        $this->_controller = 'adminhtml_canvas';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('magefly_wallmur')->__('Save Canvas')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('magefly_wallmur')->__('Delete Canvas')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('magefly_wallmur')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_canvas') && Mage::registry('current_canvas')->getId()) {
            return Mage::helper('magefly_wallmur')->__(
                "Edit Canvas '%s'",
                $this->escapeHtml(Mage::registry('current_canvas')->getCanvasCode())
            );
        } else {
            return Mage::helper('magefly_wallmur')->__('Add Canvas');
        }
    }
}
