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
 * Canvas admin grid block
 *
 * @category    Magefly
 * @package     Magefly_Wallmur
 * @author      Ultimate Module Creator
 */
class Magefly_Wallmur_Block_Adminhtml_Canvas_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('canvasGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Magefly_Wallmur_Block_Adminhtml_Canvas_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('magefly_wallmur/canvas')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Magefly_Wallmur_Block_Adminhtml_Canvas_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('magefly_wallmur')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'canvas_code',
            array(
                'header'    => Mage::helper('magefly_wallmur')->__('Canvas Code'),
                'align'     => 'left',
                'index'     => 'canvas_code',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('magefly_wallmur')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('magefly_wallmur')->__('Enabled'),
                    '0' => Mage::helper('magefly_wallmur')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'split_count',
            array(
                'header' => Mage::helper('magefly_wallmur')->__('Split Count'),
                'index'  => 'split_count',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'width',
            array(
                'header' => Mage::helper('magefly_wallmur')->__('Width'),
                'index'  => 'width',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'height',
            array(
                'header' => Mage::helper('magefly_wallmur')->__('Height'),
                'index'  => 'height',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'price',
            array(
                'header' => Mage::helper('magefly_wallmur')->__('Price'),
                'index'  => 'price',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'special_price',
            array(
                'header' => Mage::helper('magefly_wallmur')->__('Special Price'),
                'index'  => 'special_price',
                'type'=> 'number',

            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('magefly_wallmur')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('magefly_wallmur')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('magefly_wallmur')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('magefly_wallmur')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('magefly_wallmur')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('magefly_wallmur')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('magefly_wallmur')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Magefly_Wallmur_Block_Adminhtml_Canvas_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('canvas');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('magefly_wallmur')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('magefly_wallmur')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('magefly_wallmur')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('magefly_wallmur')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('magefly_wallmur')->__('Enabled'),
                            '0' => Mage::helper('magefly_wallmur')->__('Disabled'),
                        )
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Magefly_Wallmur_Model_Canvas
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Magefly_Wallmur_Block_Adminhtml_Canvas_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param Magefly_Wallmur_Model_Resource_Canvas_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Magefly_Wallmur_Block_Adminhtml_Canvas_Grid
     * @author Ultimate Module Creator
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
