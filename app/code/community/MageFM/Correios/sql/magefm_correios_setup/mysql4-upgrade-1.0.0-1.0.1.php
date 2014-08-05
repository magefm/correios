<?php

$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('sales/quote_item')}` ADD `product_width` INT unsigned null;
    ALTER TABLE `{$this->getTable('sales/quote_item')}` ADD `product_length` INT unsigned null;
    ALTER TABLE `{$this->getTable('sales/quote_item')}` ADD `product_height` INT unsigned null;

    ALTER TABLE `{$this->getTable('sales/order_item')}` ADD `product_width` INT unsigned null;
    ALTER TABLE `{$this->getTable('sales/order_item')}` ADD `product_length` INT unsigned null;
    ALTER TABLE `{$this->getTable('sales/order_item')}` ADD `product_height` INT unsigned null;
");

$this->endSetup();
