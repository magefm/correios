<?php

class MageFM_Correios_Model_Resource_Entity_Item_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{

    public function _construct()
    {
        $this->_init('magefm_correios/entity_item');
    }

}
