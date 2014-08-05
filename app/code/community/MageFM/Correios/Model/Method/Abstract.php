<?php

abstract class MageFM_Correios_Model_Method_Abstract
{

    protected $carrierCode;
    protected $carrierName;

    public function getCarrierCode()
    {
        return $this->carrierCode;
    }

    public function getCarrierName()
    {
        return $this->carrierName;
    }

    public function setCarrierCode($carrierCode)
    {
        $this->carrierCode = $carrierCode;
    }

    public function setCarrierName($carrierName)
    {
        $this->carrierName = $carrierName;
    }

    public function getMaxWidth()
    {
        return 105;
    }

    public function getMaxLength()
    {
        return 105;
    }

    public function getMaxHeight()
    {
        return 105;
    }

    public function getDefaultWidth()
    {
        return (int) Mage::getStoreConfig('carriers/magefm_correios/default_width');
    }

    public function getDefaultLength()
    {
        return (int) Mage::getStoreConfig('carriers/magefm_correios/default_length');
    }

    public function getDefaultHeight()
    {
        return (int) Mage::getStoreConfig('carriers/magefm_correios/default_height');
    }

    public function calculateRate($cepOrigin, $cep, $items)
    {
        $packages = $this->splitInPackages($items);

        if (!$packages) {
            return false;
        }

        $price = 0;
        $cost = 0;
        $days = null;

        foreach ($packages as $package) {
            $rate = $this->calculatePackageRate($cepOrigin, $cep, $package['weight']);

            if (!$rate) {
                return false;
            }

            $price += $rate->getPrice();
            $cost += $rate->getPrice();
            $rateDays = $rate->getDays();

            if ($rateDays === -1) {
                $days = -1;
            }

            if ($days !== -1 && !is_null($rateDays) && (is_null($days) || $rateDays > $days)) {
                $days = $rateDays;
            }
        }

        $additionalDays = (int) Mage::getStoreConfig('carriers/magefm_correios/additional_days');

        if ($additionalDays > 0 && $days != -1) {
            $days += $additionalDays;
        }

        $additionalPrice = (float) Mage::getStoreConfig('carriers/magefm_correios/additional_price');

        if ($additionalPrice > 0) {
            $price += $additionalPrice;
        }

        return $this->buildResult($price, $cost, $days);
    }

    protected function buildResult($price, $cost = null, $days = null)
    {
        $result = Mage::getModel('shipping/rate_result_method');
        $result->setCarrier($this->getCarrierCode());
        $result->setCarrierTitle($this->getCarrierName());
        $result->setMethod($this->getCode());
        $result->setPrice($price);

        if ($cost !== null) {
            $result->setCost($cost);
        }

        if ($days === -1) {
            $result->setMethodTitle(Mage::helper('magefm_correios')->__('%s (ask for delivery time)', $this->getName()));
        } elseif ($days !== null) {
            $result->setMethodTitle(Mage::helper('magefm_correios')->__('%s (delivery in %d business days)', $this->getName(), $days));
        } else {
            $result->setMethodTitle($this->getName());
        }

        return $result;
    }

    protected function splitInPackages($items)
    {
        $packages = array();

        foreach ($items as $item) {
            $unitWeight = $item->getProduct()->getWeight();
            $qty = $item->getQty();

            if ($unitWeight > $this->getWeightLimit()) {
                return false;
            }

            $width = (int) $item->getData('product_width');
            $length = (int) $item->getData('product_length');
            $height = (int) $item->getData('product_height');

            if ($width === 0) {
                $width = $this->getDefaultWidth();
            }

            if ($length === 0) {
                $length = $this->getDefaultLength();
            }

            if ($height === 0) {
                $height = $this->getDefaultHeight();
            }

            if ($width > $this->getMaxWidth() || $height > $this->getMaxHeight() || $length > $this->getMaxLength()) {
                return false;
            }

            /**
             * @TODO calculate using bin packing instead of cubicWeight
             */
            $cubicWeight = $width * $length * $height / 6000;

            if ($cubicWeight < 10) {
                $allocateWeight = $unitWeight;
            } else {
                $allocateWeight = max($unitWeight, $cubicWeight);
            }

            for ($i = 0; $i < $qty; $i++) {
                $packages = $this->allocateItemInPackage($packages, $item->getId(), $allocateWeight);
            }
        }

        return $packages;
    }

    /**
     * @TODO implement package volume
     */
    protected function allocateItemInPackage($packages, $itemId, $weight)
    {
        foreach ($packages as &$package) {
            $totalWeight = $package['weight'] + $weight;

            if ($totalWeight > $this->getWeightLimit()) {
                continue;
            }

            $package['weight'] = $totalWeight;

            if (isset($package['items'][$itemId])) {
                $package['items'][$itemId] += 1;
            } else {
                $package['items'][$itemId] = 1;
            }

            return $packages;
        }

        $packages[] = array('weight' => $weight, 'items' => array($itemId => 1));

        return $packages;
    }

    protected function calculatePackageRate($cepOrigin, $cep, $weight)
    {
        $weight = $this->roundWeight($weight);
        $rate = $this->getRateFromDatabase($cepOrigin, $cep, $weight);

        if ($rate) {
            return $rate;
        }

        $rate = $this->getRateFromWebservice($cepOrigin, $cep, $weight);

        if ($rate) {
            return $rate;
        }

        $rate = $this->getRateFromRange($cepOrigin, $cep, $weight);

        if ($rate) {
            return $rate;
        }

        return false;
    }

    protected function getRateFromDatabase($cepOrigin, $cep, $weight)
    {
        if (Mage::helper('magefm_correios')->isSpecificCacheEnabled() === false) {
            return;
        }

        $timeout = new Zend_Date();
        $timeout->sub((int) Mage::getStoreConfig('carriers/magefm_correios/timeout_days'), Zend_Date::DAY);

        $collection = Mage::getModel('magefm_correios/entity_item')->getCollection();
        $collection->addFieldToFilter('method', $this->getCode());
        $collection->addFieldToFilter('cep_origin', $cepOrigin);
        $collection->addFieldToFilter('cep_destination', $cep);
        $collection->addFieldToFilter('weight', $weight);
        $collection->addFieldToFilter('last_update', array('gteq' => $timeout->toString('YYYY-MM-dd HH:mm:ss')));

        if ($collection->count() === 0) {
            return false;
        }

        return $collection->getFirstItem();
    }

    protected function roundWeight($weight)
    {
        if ($weight <= 0.3) {
            return 0.3;
        }

        return ceil($weight);
    }

    protected function getRateFromWebservice($cepOrigin, $cep, $weight)
    {
        $administrativeCode = Mage::getStoreConfig('carriers/magefm_correios/administrative_code');
        $administrativePassword = Mage::getStoreConfig('carriers/magefm_correios/administrative_password');
        $activeMethods = explode(',', Mage::getStoreConfig('carriers/magefm_correios/methods'));
        $services = array();

        /** @TODO use package volume instead of default values */
        $height = $this->getDefaultHeight();
        $width = $this->getDefaultWidth();
        $length = $this->getDefaultLength();

        foreach ($activeMethods as $method) {
            $services[] = Mage::helper('magefm_correios')->getServiceCodeByMethod($method);
        }

        $requestedItem = false;

        try {
            $xml = Mage::helper('magefm_correios')->getCorreiosXML($services, $cepOrigin, $cep, $weight, $height, $width, $length, $administrativeCode, $administrativePassword);

            if (!$xml) {
                return false;
            }

            foreach ($xml->cServico as $service) {
                if ((string) $service->Erro != '0' && (string) $service->Erro !== '010') {
                    continue;
                }

                $method = Mage::helper('magefm_correios')->getMethodByServiceCode((string) $service->Codigo);
                $item = Mage::getModel('magefm_correios/entity_item');
                $item->setMethod($method);
                $item->setWeight($weight);
                $item->setCepOrigin($cepOrigin);
                $item->setCepDestination($cep);
                $item->setPrice(str_replace(',', '.', (string) $service->Valor));
                $item->setDays((string) $service->PrazoEntrega);
                $item->setLastUpdate(new Zend_Db_Expr('NOW()'));

                if ((string) $service->Erro === '010') {
                    $item->setDays(-1);
                    $item->setMessage((string) $service->MsgErro);
                } elseif (Mage::helper('magefm_correios')->isSpecificCacheEnabled()) {
                    $item->save();
                }

                if ($item->getMethod() == $this->getCode()) {
                    $requestedItem = $item;
                }
            }
        } catch (Exception $e) {
            Mage::log("Exception " . get_class($e) . ": " . $e->getMessage(), null, 'magefm_correios.log');
            Mage::logException($e);
            return false;
        }

        return $requestedItem;
    }

    protected function getRateFromRange($cepOrigin, $cep, $weight)
    {
        $collection = Mage::getModel('magefm_correios/entity_range')->getCollection();
        $collection->addFieldToFilter('method', $this->getCode());
        $collection->addFieldToFilter('cep_origin', $cepOrigin);
        $collection->addFieldToFilter('cep_destination_from', array('lteq' => $cep));
        $collection->addFieldToFilter('cep_destination_to', array('gteq' => $cep));
        $collection->addFieldToFilter('weight', $weight);

        if ($collection->count() === 0) {
            return false;
        }

        return $collection->getFirstItem();
    }

}
