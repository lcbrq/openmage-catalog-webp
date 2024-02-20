<?php

class LCB_CatalogWebP_Model_Observer
{
    /**
     * @var bool
     */
    private $isEnabled;

    public function __construct()
    {
        $this->isEnabled = Mage::helper('core')->isModuleEnabled('LCB_CatalogWebP');
    }

    /**
     * @param  Varien_Event_Observer $observer
     * @return void
     */
    public function onCatalogProductLoadAfter(Varien_Event_Observer $observer): void
    {
        $product = $observer->getProduct();
        if ($this->isEnabled && $product) {
            $helper = Mage::helper('lcb_catalogwebp');
            $image = $product->getImage();
            if ($image && $image !== 'no_selection' && strtolower(substr($image, -4)) !== 'webp') {
                try {
                    $product->setImage($helper->convert($image));
                } catch (Exception $e) {
                    Mage::logException($e);
                }
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
            if ($this->isEnabled && $product) {
                $helper = Mage::helper('lcb_catalogwebp');
                $image = $product->getSmallImage();
                if ($image && $image !== 'no_selection' && strtolower(substr($image, -4)) !== 'webp') {
                    try {
                        $product->setSmallImage($helper->convert($image));
                    } catch (Exception $e) {
                        Mage::logException($e);
                    }
                }
            }
        }
    }

    /**
     * @param  Varien_Event_Observer $observer
     * @return void
     */
    public function onCatalogProductSaveAfter(Varien_Event_Observer $observer): void
    {
        $product = $observer->getProduct();
        if ($this->isEnabled && $product) {
            try {
                $webPath = Mage::helper('lcb_catalogwebp')->refreshImages($product);
            } catch (Exception $e) {
                Mage::logException($e);
            }
        }
    }
}
