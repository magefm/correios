<?php

class MageFM_Correios_Model_Entity_Range extends Mage_Core_Model_Abstract
{

    /** @TODO weight 0.3 é convertido pra 0 */
    public function _construct()
    {
        $this->_init('magefm_correios/entity_range');
    }

    protected function _beforeSave()
    {
        // Sanitize
        $cepOrigin = $this->sanitizeCep($this->getData('cep_origin'));
        $cepDestinationFrom = $this->sanitizeCep($this->getData('cep_destination_from'));
        $cepDestinationTo = $this->sanitizeCep($this->getData('cep_destination_to'));

        // Validate
        if (empty($cepOrigin)) {
            Mage::throwException(Mage::helper('magefm_correios')->__('Origin is invalid.'));
        }

        if (empty($cepDestinationFrom)) {
            Mage::throwException(Mage::helper('magefm_correios')->__('Destination from is invalid.'));
        }

        if (empty($cepDestinationTo)) {
            Mage::throwException(Mage::helper('magefm_correios')->__('Destination to is invalid.'));
        }

        // Validate range conflict
        $collection = $this->getCollection();
        $collection->addFieldToFilter('cep_origin', $this->getCepOrigin());
        $collection->addFieldToFilter('weight', $this->getWeight());
        $collection->addFieldToFilter('method', $this->getMethod());
        $collection->addCepBetweenFilter($this->getCepDestinationFrom(), $this->getCepDestinationTo());

        if ($this->getId() > 0) {
            $collection->addFieldToFilter('id', array('neq' => $this->getId()));
        }

        if ($collection->getSize() > 0) {
            Mage::throwException(Mage::helper('magefm_correios')->__('The range specified conflits with other ranges.'));
        }

        parent::_beforeSave();
    }

    protected function sanitizeCep($cep)
    {
        $cep = preg_replace('/[^0-9]/s', '', $cep);

        if (strlen($cep) > 8 || strlen($cep) === 0) {
            return false;
        }

        return $cep;
    }

}
