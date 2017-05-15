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
 * Canvas model
 *
 * @category    Magefly
 * @package     Magefly_Wallmur
 * @author      Ultimate Module Creator
 */
class Magefly_Wallmur_Model_Canvas extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'magefly_wallmur_canvas';
    const CACHE_TAG = 'magefly_wallmur_canvas';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'magefly_wallmur_canvas';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'canvas';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('magefly_wallmur/canvas');
    }

    /**
     * before save canvas
     *
     * @access protected
     * @return Magefly_Wallmur_Model_Canvas
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save canvas relation
     *
     * @access public
     * @return Magefly_Wallmur_Model_Canvas
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
    /**
      * get Fit Shape
      *
      * @access public
      * @return array
      * @author Ultimate Module Creator
      */
    public function getFitShape()
    {
        if (!$this->getData('fit_shape')) {
            return explode(',', $this->getData('fit_shape'));
        }
        return $this->getData('fit_shape');
    }
}
