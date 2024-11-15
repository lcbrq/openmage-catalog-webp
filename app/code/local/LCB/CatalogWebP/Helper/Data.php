<?php

class LCB_CatalogWebp_Helper_Data
{
    /**
     * @var string
     */
    private $storagePrefix = 'webp';

    /**
     * @param  string $image
     * @return string
     */
    public function convert($image): string
    {
        $pathInfo = pathinfo($image);
        if (strtolower($pathInfo['extension']) !== 'webp') {
            $fileName = $pathInfo['filename'];
            $imageFolder = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'product';
            $webFolder = $this->getWebFolder();
            $webpImage = $pathInfo['dirname'] . DS . $fileName . '.webp';
            $webpDir = $webFolder . $pathInfo['dirname'];
            $webpPath = $webFolder . $webpImage;
            if (file_exists($webpPath)) {
                return $this->storagePrefix . $webpImage;
            }

            $imagePath = $imageFolder . $image;
            if (file_exists($imagePath)) {
                if (!file_exists($webpPath)) {
                    if (!file_exists($webpDir)) {
                        @mkdir($webpDir, 0777, true);
                    }
                    $command = "cwebp $imagePath -o $webpPath 2>&1";
                    exec($command, $output, $exitCode);
                    if (!$exitCode) {
                        return $this->storagePrefix . $webpImage;
                    }
                }
            }
        }

        return $image;
    }

    /**
     * @param Mage_Catalog_Model_Product
     * @return void
     */
    public function refreshImages($product)
    {
        foreach ([$product->getImage(), $product->getSmallImage()] as $image) {
            if ($image === 'no_selection') {
                continue;
            }
            $pathInfo = pathinfo($image);
            $webpImage = $pathInfo['dirname'] . DS . $pathInfo['filename'] . '.webp';
            $webpPath = $this->getWebFolder() . $webpImage;
            if (file_exists($webpPath)) {
                unlink($webpPath);
            }
        }

        Mage::dispatchEvent('lcb_catalogwebp_images_refresh', array('product' => $product));
    }

    /**
     * @param  string $image
     * @return string
     */
    public function getWebPath($image)
    {
        $pathInfo = pathinfo($image);
    }

    /**
     * @return string
     */
    public function getWebFolder()
    {
        return Mage::getBaseDir('media') . DS . 'catalog' . DS . 'product' . DS . $this->storagePrefix;
    }
}
