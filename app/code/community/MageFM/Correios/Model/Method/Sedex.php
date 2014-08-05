<?php

class MageFM_Correios_Model_Method_Sedex extends MageFM_Correios_Model_Method_Abstract implements MageFM_Correios_Model_Method_Interface
{

    public function getCode()
    {
        return 'sedex';
    }

    public function getName()
    {
        return Mage::helper('magefm_correios')->__('SEDEX');
    }

    public function getWeightLimit()
    {
        return 30;
    }

}
