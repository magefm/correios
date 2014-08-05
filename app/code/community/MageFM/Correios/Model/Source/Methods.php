<?php

class MageFM_Correios_Model_Source_Methods
{

    public function toOptionArray()
    {
        return array(
            array('value' => 'pac', 'label' => Mage::helper('magefm_correios')->__('PAC')),
            array('value' => 'paccontrato', 'label' => Mage::helper('magefm_correios')->__('PAC (com contrato)')),
            array('value' => 'sedex', 'label' => Mage::helper('magefm_correios')->__('Sedex')),
            array('value' => 'sedexcontrato', 'label' => Mage::helper('magefm_correios')->__('Sedex (com contrato)')),
            array('value' => 'sedex10', 'label' => Mage::helper('magefm_correios')->__('Sedex 10')),
            array('value' => 'sedexhoje', 'label' => Mage::helper('magefm_correios')->__('Sedex Hoje')),
            array('value' => 'esedex', 'label' => Mage::helper('magefm_correios')->__('E-Sedex')),
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
