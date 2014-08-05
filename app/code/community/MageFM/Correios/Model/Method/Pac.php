<?php

class MageFM_Correios_Model_Method_Pac extends MageFM_Correios_Model_Method_Abstract implements MageFM_Correios_Model_Method_Interface
{

    public function getCode()
    {
        return 'pac';
    }

    public function getName()
    {
        return Mage::helper('magefm_correios')->__('PAC');
    }

    public function getWeightLimit()
    {
        return 30;
    }

}
