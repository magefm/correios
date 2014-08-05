<?php

$this->startSetup();

$this->addAttribute('catalog_product', 'width', array(
    'type' => 'varchar',
    'label' => 'Largura (cm)',
    'input' => 'text',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$this->addAttribute('catalog_product', 'length', array(
    'type' => 'varchar',
    'label' => 'Comprimento (cm)',
    'input' => 'text',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$this->addAttribute('catalog_product', 'height', array(
    'type' => 'varchar',
    'label' => 'Altura (cm)',
    'input' => 'text',
    'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$this->run("
CREATE TABLE IF NOT EXISTS `{$this->getTable('magefm_correios/item')}` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `method` VARCHAR(50) NOT NULL,
    `weight` DOUBLE UNSIGNED NOT NULL,
    `cep` BIGINT NOT NULL,
    `price` DOUBLE UNSIGNED NOT NULL,
    `days` INT UNSIGNED NULL,
    `last_update` DATETIME NOT NULL,
    UNIQUE INDEX cep_method_weight (cep, method, weight)
) ENGINE = InnoDB;
");

$this->endSetup();
