<?php

class MageFM_Correios_Model_Method extends Mage_Shipping_Model_Carrier_Abstract
{

    protected $_code = 'magefm_correios';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $cep = Mage::helper('magefm_correios')->sanitizeCep($request->getDestPostcode());

        if (!$cep) {
            return false;
        }

        $cepOrigin = Mage::helper('magefm_correios')->sanitizeCep(Mage::getStoreConfig('shipping/origin/postcode'));

        if (!$cepOrigin) {
            return false;
        }

        $items = $request->getAllItems();
        $result = Mage::getModel('shipping/rate_result');
        $activeMethods = $this->getActiveMethods();

        foreach ($activeMethods as $method) {
            $rateResultMethod = $method->calculateRate($cepOrigin, $cep, $items);

            if (!$rateResultMethod) {
                continue;
            }

            $result->append($rateResultMethod);
        }

        return $result;
    }

    public function isTrackingAvailable()
    {
        return true;
    }

    public function getTrackingInfo($tracking)
    {
        $tracking = trim($tracking);
        try {
            $url = 'http://websro.correios.com.br/sro_bin/txect01$.QueryList?P_LINGUA=001&P_TIPO=001&P_COD_UNI=' . $tracking;

            $client = new Zend_Http_Client();
            $client->setUri($url);
            $body = $client->request()->getBody();

            if (!preg_match('#<table ([^>]+)>(.*?)</table>#is', $body, $matches)) {
                throw new Exception();
            }

            $table = $matches[2];

            if (!preg_match_all('/<tr>(.*)<\/tr>/i', $table, $columns, PREG_SET_ORDER)) {
                throw new Exception();
            }

            $progress = array();

            for ($i = 0; $i < count($columns); $i++) {
                $column = $columns[$i][1];

                $description = null;
                $found = false;

                if (preg_match('/<td rowspan="?2"?/i', $column) && preg_match('/<td rowspan="?2"?>(.*)<\/td><td>(.*)<\/td><td><font color="[A-Z0-9]{6}">(.*)<\/font><\/td>/i', $column, $matches)) {
                    if (preg_match('/<td colspan="?2"?>(.*)<\/td>/i', $columns[$i + 1][1], $matchesDescription)) {
                        $description = str_replace('  ', '', utf8_encode($matchesDescription[1]));
                    }

                    $found = true;
                } elseif (preg_match('/<td rowspan="?1"?>(.*)<\/td><td>(.*)<\/td><td><font color="[A-Z0-9]{6}">(.*)<\/font><\/td>/i', $column, $matches)) {
                    $found = true;
                }

                if (!$found) {
                    continue;
                }

                $date = new Zend_Date($matches[1], 'dd/MM/YYYY HH:mm', $locale);

                $track = array(
                    'deliverydate' => $date->toString('YYYY-MM-dd'),
                    'deliverytime' => $date->toString('HH:mm:00'),
                    'deliverylocation' => htmlentities(utf8_encode($matches[2])),
                    'status' => utf8_encode($matches[3]),
                    'activity' => utf8_encode($matches[3]),
                );

                if (!empty($description)) {
                    $track['activity'] = utf8_encode($matches[3]) . ' - ' . $description;
                }

                $progress[] = $track;
            }

            if (empty($progress)) {
                throw new Exception();
            }

            $track = $progress[0];
            $track['progressdetail'] = $progress;

            $result = Mage::getModel('shipping/tracking_result_status');
            $result->setTracking($tracking);
            $result->setCarrier($this->_code);
            $result->setCarrierTitle($this->getConfigData('title'));
            $result->addData($track);
            return $result;
        } catch (Exception $e) {
            $result = Mage::getModel('shipping/tracking_result_error');
            $result->setTracking($tracking);
            $result->setCarrier($this->_code);
            $result->setCarrierTitle($this->getConfigData('title'));
            $result->setErrorMessage($e->getMessage());
            return $result;
        }
    }

    protected function getActiveMethods()
    {
        $activeMethods = explode(',', $this->getConfigData('methods'));
        $methods = array();

        foreach ($activeMethods as $methodName) {
            $method = $this->getMethod($methodName);

            if ($method instanceof MageFM_Correios_Model_Method_Interface) {
                $method->setCarrierCode($this->getCarrierCode());
                $method->setCarrierName($this->getConfigData('title'));

                $methods[] = $method;
            }
        }

        return $methods;
    }

    protected function getMethod($name)
    {
        return Mage::getModel('magefm_correios/method_' . $name);
    }

}
