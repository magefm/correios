<?php

class MageFM_Correios_Model_Source_Freeshipping
{

    public function toOptionArray()
    {
        return array(
            array('value' => '', 'label' => Mage::helper('magefm_correios')->__('None')),
            array('value' => 'cheapest', 'label' => Mage::helper('magefm_correios')->__('Cheapest')),
            array('value' => 'fastest', 'label' => Mage::helper('magefm_correios')->__('Fastest')),
            array('value' => 'specific', 'label' => Mage::helper('magefm_correios')->__('Specific method')),
        );
    }

    public function toKeyValueArray()
    {
        $result = array();

        foreach ($this->toOptionArray() as $item) {
            $result[$item['value']] = $item['label'];
        }

        return $result;
    }

}
