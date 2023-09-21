<?php

class LCB_CatalogWebp_Helper_Data
{
    /**
     * @param  string $image
     * @return string
     */
    public function convert(string $image): string
    {
        $pathInfo = pathinfo($image);
        if (strtolower($pathInfo['extension']) !== 'webp') {
            $imageFolder = Mage::getBaseDir('media') . DS . 'catalog' . DS . 'product';
            $webpImage = $pathInfo['dirname'] . DS . $pathInfo['filename'] . '.webp';
            $webpPath = $imageFolder . $webpImage;
            if (file_exists($webpPath)) {
                return $webpImage;
            }

            $imagePath = $imageFolder . $image;
            if (file_exists($imagePath)) {
                exec("cwebp $imagePath -o $webpPath 2>&1", $output, $exitCode);
                if (!$exitCode) {
                    return $webpImage;
                }
            }
        }

        return $image;
    }
}
