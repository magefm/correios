<?php

class MageFM_Correios_Adminhtml_Magefm_Correios_RangeController extends Mage_Adminhtml_Controller_Action
{

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('system/magefm_correios');
        $this->renderLayout();
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('magefm_correios/entity_range');

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('magefm_correios')->__('This range no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        } else {
            $cepOrigin = Mage::helper('magefm_correios')->sanitizeCep(Mage::getStoreConfig('shipping/origin/postcode'));
            $model->setData('cep_origin', $cepOrigin);
        }

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('magefm_correios_range', $model);

        $this->loadLayout();
        $this->_setActiveMenu('system/magefm_correios');
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost()) {
            $id = $this->getRequest()->getParam('id');
            $model = Mage::getModel('magefm_correios/entity_range')->load($id);
            if (!$model->getId() && $id) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('magefm_correios')->__('This range no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }

            $model->setData($data);

            try {
                $model->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('magefm_correios')->__('The range has been saved.'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setFormData($data);

                if ($id) {
                    $this->_redirect('*/*/edit', array('id' => $id));
                } else {
                    $this->_redirect('*/*/new');
                }
            }
        }
    }

    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $model = Mage::getModel('magefm_correios/entity_range');
                $model->load($id);
                $model->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('magefm_correios')->__('The range has been deleted.'));
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $id));
                return;
            }
        }

        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('magefm_correios')->__('This range no longer exists.'));
        $this->_redirect('*/*/');
    }

    public function massDeleteAction()
    {
        $ids = $this->getRequest()->getParam('range_id');

        if (!is_array($ids) || count($ids) === 0) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('magefm_correios')->__('Please select range(s).'));
            $this->_redirect('*/*/index');
            return;
        }

        try {
            $range = Mage::getModel('magefm_correios/entity_range');

            foreach ($ids as $id) {
                $range->load($id)->delete();
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('magefm_correios')->__('Total of %d record(s) were deleted.', count($ids)));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }

}
