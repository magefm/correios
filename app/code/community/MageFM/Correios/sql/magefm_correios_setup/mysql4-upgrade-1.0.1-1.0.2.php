<?php

$this->startSetup();

$this->run("

ALTER TABLE `{$this->getTable('magefm_correios/item')}`
   CHANGE `cep` `cep_destination` BIGINT(20) NOT NULL;

ALTER TABLE `{$this->getTable('magefm_correios/item')}`
    ADD `cep_origin` BIGINT NOT NULL AFTER `weight`;

ALTER TABLE `{$this->getTable('magefm_correios/item')}`
    DROP INDEX cep_method_weight;

ALTER TABLE `{$this->getTable('magefm_correios/item')}`
    ADD UNIQUE origin_destination_method_weight (cep_origin, cep_destination, method, weight);
    
");

$cepOrigin = Mage::helper('magefm_correios')->sanitizeCep(Mage::getStoreConfig('shipping/origin/postcode'));

if ((int) $cepOrigin === 0) {
    $this->run("DELETE FROM `{$this->getTable('magefm_correios/item')}`");
} else {
    $this->run("UPDATE `{$this->getTable('magefm_correios/item')}` SET cep_origin = '{$cepOrigin}'");
}

$this->endSetup();
