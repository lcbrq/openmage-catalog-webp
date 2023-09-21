<?php

class LCB_CatalogWebP_Model_Observer
{
    /**
     * @param  Varien_Event_Observer $observer
     * @return void
     */
    public function onCatalogProductLoadAfter(Varien_Event_Observer $observer): void
    {
        $product = $observer->getProduct();
        if ($product) {
            $helper = Mage::helper('lcb_catalogwebp');
            $image = $product->getImage();
            if ($image && $image !== 'no_selection' && strtolower(substr($image, -4)) !== 'webp') {
                $product->setImage($helper->convert($image));
            }
        }
    }

    /**
     * @param  Varien_Event_Observer $observer
     * @return void
     */
    public function onCatalogProductCollectionLoadAfter(Varien_Event_Observer $observer): void
    {
        $collection = $observer->getCollection();
        foreach ($collection as $product) {
            if ($product) {
                $helper = Mage::helper('lcb_catalogwebp');
                $image = $product->getSmallImage();
                if ($image && $image !== 'no_selection' && strtolower(substr($image, -4)) !== 'webp') {
                    $product->setSmallImage($helper->convert($image));
                }
            }
        }
    }
}
