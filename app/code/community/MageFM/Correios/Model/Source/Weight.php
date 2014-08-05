<?php

class MageFM_Correios_Model_Source_Weight
{

    public function toOptionArray()
    {
        $weights = array(array('value' => '0.3', 'label' => 0.3));

        for ($i = 1; $i <= 30; $i++) {
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
