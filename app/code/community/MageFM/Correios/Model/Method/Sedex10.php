<?php

class MageFM_Correios_Model_Method_Sedex10 extends MageFM_Correios_Model_Method_Abstract implements MageFM_Correios_Model_Method_Interface
{

    public function getCode()
    {
        return 'sedex10';
    }

    public function getName()
    {
        return Mage::helper('magefm_correios')->__('SEDEX 10');
    }

    public function getWeightLimit()
    {
        return 10;
    }

}
