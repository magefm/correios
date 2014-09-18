<?php

class MageFM_Correios_Model_Method_Paccontrato extends MageFM_Correios_Model_Method_Pac implements MageFM_Correios_Model_Method_Interface
{

    public function getCode()
    {
        return 'paccontrato';
    }

    public function getName()
    {
        return Mage::helper('magefm_correios')->__('PAC');
    }

}
