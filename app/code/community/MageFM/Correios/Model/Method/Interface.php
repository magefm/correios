<?php

interface MageFM_Correios_Model_Method_Interface
{

    public function calculateRate($cepOrigin, $cep, $packages);

    public function getCarrierCode();

    public function getCarrierName();

    public function getCode();

    public function getName();

    public function setCarrierCode($carrierCode);

    public function setCarrierName($carrierName);
}
