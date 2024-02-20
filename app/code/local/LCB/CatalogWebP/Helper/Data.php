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
            $imageFolder = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'product';
            $webFolder = $this->getWebFolder();
            $webpImage = $pathInfo['dirname'] . DS . $pathInfo['filename'] . '.webp';
            $webpDir = $webFolder . $pathInfo['dirname'];
            $webpPath = $webFolder . $webpImage;
            if (file_exists($webpPath)) {
                return $this->storagePrefix . $webpImage;
            }

            $imagePath = $imageFolder . $image;
            if (file_exists($imagePath)) {
                if (!file_exists($webpPath) && @mkdir($webpDir, 0777, true)) {
                    exec("cwebp $imagePath -o $webpPath 2>&1", $output, $exitCode);
                    if (!$exitCode) {
                        return $this->storagePrefix . $webpImage;
                    }
                }
            }
        }

        return $image;
    }

    /**
     * @return void
     */
    public function refreshImages()
    {
        foreach ([$product->getImage(), $product->getSmallImage()] as $image) {
            if ($image === 'no_selection') {
                continue;
            }
            $pathInfo = pathinfo($image);
            if (strtolower($pathInfo['extension']) === 'webp') {
                $webpImage = $pathInfo['dirname'] . DS . $pathInfo['filename'] . '.webp';
                $webpPath = $this->getWebFolder() . $webpImage;
                unlink($webpPath);
            }
        }
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
