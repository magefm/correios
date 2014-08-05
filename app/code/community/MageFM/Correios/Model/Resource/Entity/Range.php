<?php

class MageFM_Correios_Model_Resource_Entity_Range extends Mage_Core_Model_Resource_Db_Abstract
{

    public function _construct()
    {
        $this->_init('magefm_correios/range', 'id');
    }

}
