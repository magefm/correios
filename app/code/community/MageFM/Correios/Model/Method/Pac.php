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

    public function roundWeight($weight)
    {
        if ($weight > $this->getWeightLimit()) {
            return false;
        }

        if ($weight <= 0.5) {
            return 0.5;
        }

        if ($weight <= 1) {
            return 1;
        }

        if ($weight <= 1.5) {
            return 1.5;
        }

        return ceil($weight);
    }

}
