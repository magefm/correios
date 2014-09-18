<?php

class MageFM_Correios_Model_Method_Sedexcontrato extends MageFM_Correios_Model_Method_Sedex implements MageFM_Correios_Model_Method_Interface
{

    public function getCode()
    {
        return 'sedexcontrato';
    }

    public function getName()
    {
        return Mage::helper('magefm_correios')->__('SEDEX');
    }

}
