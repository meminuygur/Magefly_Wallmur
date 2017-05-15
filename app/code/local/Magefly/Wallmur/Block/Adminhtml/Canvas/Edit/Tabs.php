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
 * Canvas admin edit tabs
 *
 * @category    Magefly
 * @package     Magefly_Wallmur
 * @author      Ultimate Module Creator
 */
class Magefly_Wallmur_Block_Adminhtml_Canvas_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('canvas_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('magefly_wallmur')->__('Canvas'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Magefly_Wallmur_Block_Adminhtml_Canvas_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_canvas',
            array(
                'label'   => Mage::helper('magefly_wallmur')->__('Canvas'),
                'title'   => Mage::helper('magefly_wallmur')->__('Canvas'),
                'content' => $this->getLayout()->createBlock(
                    'magefly_wallmur/adminhtml_canvas_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_canvas',
                array(
                    'label'   => Mage::helper('magefly_wallmur')->__('Store views'),
                    'title'   => Mage::helper('magefly_wallmur')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'magefly_wallmur/adminhtml_canvas_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve canvas entity
     *
     * @access public
     * @return Magefly_Wallmur_Model_Canvas
     * @author Ultimate Module Creator
     */
    public function getCanvas()
    {
        return Mage::registry('current_canvas');
    }
}
