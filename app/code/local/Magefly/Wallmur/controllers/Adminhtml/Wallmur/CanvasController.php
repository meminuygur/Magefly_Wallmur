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
 * Canvas admin controller
 *
 * @category    Magefly
 * @package     Magefly_Wallmur
 * @author      Ultimate Module Creator
 */
class Magefly_Wallmur_Adminhtml_Wallmur_CanvasController extends Magefly_Wallmur_Controller_Adminhtml_Wallmur
{
    /**
     * init the canvas
     *
     * @access protected
     * @return Magefly_Wallmur_Model_Canvas
     */
    protected function _initCanvas()
    {
        $canvasId  = (int) $this->getRequest()->getParam('id');
        $canvas    = Mage::getModel('magefly_wallmur/canvas');
        if ($canvasId) {
            $canvas->load($canvasId);
        }
        Mage::register('current_canvas', $canvas);
        return $canvas;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('magefly_wallmur')->__('Wallmur'))
             ->_title(Mage::helper('magefly_wallmur')->__('Canvases'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit canvas - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $canvasId    = $this->getRequest()->getParam('id');
        $canvas      = $this->_initCanvas();
        if ($canvasId && !$canvas->getId()) {
            $this->_getSession()->addError(
                Mage::helper('magefly_wallmur')->__('This canvas no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getCanvasData(true);
        if (!empty($data)) {
            $canvas->setData($data);
        }
        Mage::register('canvas_data', $canvas);
        $this->loadLayout();
        $this->_title(Mage::helper('magefly_wallmur')->__('Wallmur'))
             ->_title(Mage::helper('magefly_wallmur')->__('Canvases'));
        if ($canvas->getId()) {
            $this->_title($canvas->getCanvasCode());
        } else {
            $this->_title(Mage::helper('magefly_wallmur')->__('Add canvas'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new canvas action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save canvas - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('canvas')) {
            try {
                $canvas = $this->_initCanvas();
                $canvas->addData($data);
                $canvasMockupName = $this->_uploadAndGetName(
                    'canvas_mockup',
                    Mage::helper('magefly_wallmur/canvas_image')->getImageBaseDir(),
                    $data
                );
                $canvas->setData('canvas_mockup', $canvasMockupName);
                $canvasThumbnailName = $this->_uploadAndGetName(
                    'canvas_thumbnail',
                    Mage::helper('magefly_wallmur/canvas_image')->getImageBaseDir(),
                    $data
                );
                $canvas->setData('canvas_thumbnail', $canvasThumbnailName);
                $canvas->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('magefly_wallmur')->__('Canvas was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $canvas->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['canvas_mockup']['value'])) {
                    $data['canvas_mockup'] = $data['canvas_mockup']['value'];
                }
                if (isset($data['canvas_thumbnail']['value'])) {
                    $data['canvas_thumbnail'] = $data['canvas_thumbnail']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setCanvasData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['canvas_mockup']['value'])) {
                    $data['canvas_mockup'] = $data['canvas_mockup']['value'];
                }
                if (isset($data['canvas_thumbnail']['value'])) {
                    $data['canvas_thumbnail'] = $data['canvas_thumbnail']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('magefly_wallmur')->__('There was a problem saving the canvas.')
                );
                Mage::getSingleton('adminhtml/session')->setCanvasData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('magefly_wallmur')->__('Unable to find canvas to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete canvas - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $canvas = Mage::getModel('magefly_wallmur/canvas');
                $canvas->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('magefly_wallmur')->__('Canvas was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('magefly_wallmur')->__('There was an error deleting canvas.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('magefly_wallmur')->__('Could not find canvas to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete canvas - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $canvasIds = $this->getRequest()->getParam('canvas');
        if (!is_array($canvasIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('magefly_wallmur')->__('Please select canvases to delete.')
            );
        } else {
            try {
                foreach ($canvasIds as $canvasId) {
                    $canvas = Mage::getModel('magefly_wallmur/canvas');
                    $canvas->setId($canvasId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('magefly_wallmur')->__('Total of %d canvases were successfully deleted.', count($canvasIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('magefly_wallmur')->__('There was an error deleting canvases.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $canvasIds = $this->getRequest()->getParam('canvas');
        if (!is_array($canvasIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('magefly_wallmur')->__('Please select canvases.')
            );
        } else {
            try {
                foreach ($canvasIds as $canvasId) {
                $canvas = Mage::getSingleton('magefly_wallmur/canvas')->load($canvasId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d canvases were successfully updated.', count($canvasIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('magefly_wallmur')->__('There was an error updating canvases.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'canvas.csv';
        $content    = $this->getLayout()->createBlock('magefly_wallmur/adminhtml_canvas_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'canvas.xls';
        $content    = $this->getLayout()->createBlock('magefly_wallmur/adminhtml_canvas_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'canvas.xml';
        $content    = $this->getLayout()->createBlock('magefly_wallmur/adminhtml_canvas_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('magefly_wallmur/canvas');
    }
}
