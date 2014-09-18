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

    public function roundWeight($weight)
    {
        if ($weight > $this->getWeightLimit()) {
            return false;
        }

        if ($weight <= 0.3) {
            return 0.3;
        }

        if ($weight <= 5) {
            $full = floor($weight);
            $part = $weight - $full;

            if ($part == 0) {
                return $full;
            }

            if ($part <= 0.5) {
                $part = 0.5;
            } else {
                $full = $full + 1;
                $part = 0;
            }

            return ($full + $part);
        }

        return ceil($weight);
    }

}
