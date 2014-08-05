<?php

class MageFM_Correios_Block_Adminhtml_Range extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function __construct()
    {
        $this->_blockGroup = 'magefm_correios';
        $this->_controller = 'adminhtml_range';
        $this->_headerText = Mage::helper('magefm_correios')->__('Range');
        $this->_addButtonLabel = Mage::helper('magefm_correios')->__('New Range');
        parent::__construct();
    }

    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

}
