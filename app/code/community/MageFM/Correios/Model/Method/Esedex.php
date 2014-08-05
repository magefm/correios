<?php

class MageFM_Correios_Model_Method_Esedex extends MageFM_Correios_Model_Method_Abstract implements MageFM_Correios_Model_Method_Interface
{

    public function getCode()
    {
        return 'esedex';
    }

    public function getName()
    {
        return Mage::helper('magefm_correios')->__('e-SEDEX');
    }

    public function getWeightLimit()
    {
        return 15;
    }

}
