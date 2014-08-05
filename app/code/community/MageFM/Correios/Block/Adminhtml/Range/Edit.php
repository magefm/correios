<?php

class MageFM_Correios_Block_Adminhtml_Range_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        parent::__construct();

        $this->_objectId = 'id';
        $this->_blockGroup = 'magefm_correios';
        $this->_controller = 'adminhtml_range';

        $this->_updateButton('save', 'label', Mage::helper('magefm_correios')->__('Save'));
        $this->_updateButton('delete', 'label', Mage::helper('magefm_correios')->__('Delete'));
    }

    public function getHeaderText()
    {
        if (Mage::registry('magefm_correios_range')->getId()) {
            return Mage::helper('magefm_correios')->__("Edit Range '%s'", $this->escapeHtml(Mage::registry('magefm_correios_range')->getDescription()));
        } else {
            return Mage::helper('magefm_correios')->__('New Range');
        }
    }

}
