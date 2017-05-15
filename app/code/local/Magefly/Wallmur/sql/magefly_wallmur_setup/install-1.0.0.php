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
 * Wallmur module install script
 *
 * @category    Magefly
 * @package     Magefly_Wallmur
 * @author      Ultimate Module Creator
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('magefly_wallmur/canvas'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Canvas ID'
    )
    ->addColumn(
        'canvas_code',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Canvas Code'
    )
    ->addColumn(
        'split_count',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Split Count'
    )
    ->addColumn(
        'width',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Width'
    )
    ->addColumn(
        'height',
        Varien_Db_Ddl_Table::TYPE_INTEGER, null,
        array(
            'nullable'  => false,
            'unsigned'  => true,
        ),
        'Height'
    )
    ->addColumn(
        'price',
        Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(
            'nullable'  => false,
        ),
        'Price'
    )
    ->addColumn(
        'special_price',
        Varien_Db_Ddl_Table::TYPE_DECIMAL, '12,4',
        array(
            'nullable'  => false,
        ),
        'Special Price'
    )
    ->addColumn(
        'fit_shape',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable'  => false,
        ),
        'Fit Shape'
    )
    ->addColumn(
        'canvas_mockup',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Canvas Mockup'
    )
    ->addColumn(
        'canvas_thumbnail',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Canvas Thumbnail'
    )
    ->addColumn(
        'part_config',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Part Config'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Canvas Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Canvas Creation Time'
    ) 
    ->setComment('Canvas Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('magefly_wallmur/canvas_store'))
    ->addColumn(
        'canvas_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable'  => false,
            'primary'   => true,
        ),
        'Canvas ID'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Store ID'
    )
    ->addIndex(
        $this->getIdxName(
            'magefly_wallmur/canvas_store',
            array('store_id')
        ),
        array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'magefly_wallmur/canvas_store',
            'canvas_id',
            'magefly_wallmur/canvas',
            'entity_id'
        ),
        'canvas_id',
        $this->getTable('magefly_wallmur/canvas'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'magefly_wallmur/canvas_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $this->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Canvases To Store Linkage Table');
$this->getConnection()->createTable($table);
$this->endSetup();
