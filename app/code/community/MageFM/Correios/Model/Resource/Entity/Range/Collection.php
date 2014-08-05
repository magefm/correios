<?php

class MageFM_Correios_Model_Resource_Entity_Range_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    public function _construct()
    {
        $this->_init('magefm_correios/entity_range');
    }

    public function addCepBetweenFilter($from, $to)
    {
        $fromQuoted = $this->getConnection()->quote($from);
        $toQuoted = $this->getConnection()->quote($to);

        $where = "(cep_destination_from BETWEEN {$fromQuoted} AND {$toQuoted}) OR (cep_destination_to BETWEEN {$fromQuoted} AND {$toQuoted})";
        $this->getSelect()->where($where);
    }

}
