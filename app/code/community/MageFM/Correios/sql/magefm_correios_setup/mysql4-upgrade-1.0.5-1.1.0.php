<?php

$this->startSetup();

$this->run("

CREATE TABLE IF NOT EXISTS `{$this->getTable('magefm_correios/range')}` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `method` VARCHAR(50) NOT NULL,
    `weight` DOUBLE UNSIGNED NOT NULL,
    `cep_origin` BIGINT NOT NULL,
    `cep_destination_from` BIGINT NOT NULL,
    `cep_destination_to` BIGINT NOT NULL,
    `price` DOUBLE UNSIGNED NOT NULL,
    `days` INT UNSIGNED NULL,
    UNIQUE INDEX origin_destination_from_to_method_weight (cep_origin, cep_destination_from, cep_destination_to, method, weight)
) ENGINE = InnoDB;

");

$this->endSetup();
