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

    public function roundWeight($weight)
    {
        if ($weight > $this->getWeightLimit()) {
            return false;
        }

        if ($weight <= 0.3) {
            return 0.3;
        }

        return ceil($weight);
    }

}
