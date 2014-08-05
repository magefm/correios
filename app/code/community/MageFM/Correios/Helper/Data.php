<?php

class MageFM_Correios_Helper_Data extends Mage_Core_Helper_Abstract
{

    protected $serviceCodes = array(
        'pac' => 41106,
        'paccontrato' => 41068,
        'sedex' => 40010,
        'sedex10' => 40215,
        'sedexhoje' => 40290,
        'sedexcontrato' => 40436,
        'esedex' => 81019,
    );

    public function sanitizeCep($cep)
    {
        $cep = preg_replace('/[^0-9]/s', '', $cep);

        if (strlen($cep) !== 8) {
            return false;
        }

        return $cep;
    }

    public function getServiceCodeByMethod($method)
    {
        if (empty($this->serviceCodes[$method])) {
            return false;
        }

        return $this->serviceCodes[$method];
    }

    public function getMethodByServiceCode($serviceCode)
    {
        return array_search($serviceCode, $this->serviceCodes);
    }

    public function getCorreiosXML($services, $originCep, $destinationCep, $weight, $height, $width, $length, $administrativeCode = null, $administrativePassword = null)
    {
        $client = new Zend_Http_Client('http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx');
        $client->setConfig(array('timeout' => Mage::getStoreConfig('carriers/magefm_correios/webservice_timeout')));
        $client->setParameterGet('nCdServico', implode(',', $services));
        $client->setParameterGet('StrRetorno', 'xml');
        $client->setParameterGet('sCepOrigem', $originCep);
        $client->setParameterGet('sCepDestino', $destinationCep);
        $client->setParameterGet('nCdFormato', 1);
        $client->setParameterGet('nVlComprimento', $length);
        $client->setParameterGet('nVlAltura', $height);
        $client->setParameterGet('nVlLargura', $width);
        $client->setParameterGet('nVlDiametro', 0);
        $client->setParameterGet('sCdMaoPropria', 'N');
        $client->setParameterGet('sCdAvisoRecebimento', 'N');
        $client->setParameterGet('nVlPeso', $weight);
        $client->setParameterGet('nCdEmpresa', $administrativeCode);
        $client->setParameterGet('sDsSenha', $administrativePassword);

        $response = $client->request();

        if ($response->getStatus() != 200) {
            return false;
        }

        $xml = simplexml_load_string($response->getBody());

        if (empty($xml->cServico)) {
            return false;
        }

        return $xml;
    }

    public function isSpecificCacheEnabled()
    {
        $timeoutDays = (int) Mage::getStoreConfig('carriers/magefm_correios/timeout_days');
        return ($timeoutDays == 0 ? false : true);
    }

}
