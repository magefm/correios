<?php

class MageFM_Correios_Model_Observer
{

    public function salesQuoteItemSetProduct(Varien_Event_Observer $observer)
    {
        $product = $observer->getProduct();
        $item = $observer->getQuoteItem();

        $item->setData('product_width', $product->getData('width'));
        $item->setData('product_length', $product->getData('length'));
        $item->setData('product_height', $product->getData('height'));
    }

}
