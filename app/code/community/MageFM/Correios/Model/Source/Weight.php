<?php

class MageFM_Correios_Model_Source_Weight
{

    public function toOptionArray()
    {
        $weights = array(
            array('value' => '0.3', 'label' => 0.3),
            array('value' => '0.5', 'label' => 0.5),
            array('value' => '1.0', 'label' => 1.0),
            array('value' => '1.5', 'label' => 1.5),
            array('value' => '2.0', 'label' => 2.0),
            array('value' => '2.5', 'label' => 2.5),
            array('value' => '3.0', 'label' => 3.0),
            array('value' => '3.5', 'label' => 3.5),
            array('value' => '4.0', 'label' => 4.0),
            array('value' => '4.5', 'label' => 4.5),
        );

        for ($i = 5; $i <= 30; $i++) {
            $weights[] = array('value' => $i, 'label' => $i);
        }

        return $weights;
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
