<?php

class MageFM_Correios_Model_Resource_Entity_Item extends Mage_Core_Model_Resource_Db_Abstract
{

    public function _construct()
    {
        $this->_init('magefm_correios/item', 'id');
    }

    public function save(Mage_Core_Model_Abstract $object)
    {
        if ($object->isDeleted()) {
            return $this->delete($object);
        }

        $this->_serializeFields($object);
        $this->_beforeSave($object);

        $bind = $this->_prepareDataForSave($object);
        $this->_getWriteAdapter()->insertOnDuplicate($this->getMainTable(), $bind);
        $object->setId($this->_getWriteAdapter()->lastInsertId($this->getMainTable()));

        if ($this->_useIsObjectNew) {
            $object->isObjectNew(false);
        }

        $this->unserializeFields($object);
        $this->_afterSave($object);

        return $this;
    }

}
