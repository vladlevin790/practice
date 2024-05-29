<?php

class ProcessBase64Data {
    private $jsonData;
    private $result = [];

    public function __construct($jsonData) {
        $this->jsonData = json_decode($jsonData, true);
        if ($this->jsonData === null && json_last_error() !== JSON_ERROR_NONE) {
            $this->result = ['error' => 'Invalid JSON'];
        }
    }

    public function processProductData() {

        if (isset($this->jsonData['call'])) {

            foreach ($this->jsonData as $productName => $productData) {
                if ($this->isProductTradable($productData)) {
                    $this->processImage($productName, $productData);
                }
            }

        } else {
            $this->result = ['error' => 'Array column call doesnt exist'];
        }

        return $this->result;
    }

    private function isProductTradable($productData) {
        return (isset($productData["product_name"]['tradeble']) && $productData["product_name"]['tradeble'] == true);
    }

    private function processImage($productName, $productData) {

        if (isset($productData['image'])) {

            $imageName = isset($productData['image_name']) ? $productData['image_name'] : $productName;
            $imageLink = isset($productData['image']['link']) ? $productData['image']['link'] : '';
            $imageBase64 = isset($productData['image']['base64']) ? $productData['image']['base64'] : '';
            $matches = [];
            preg_match('#^data:image/(\w+);base64,#i', $imageBase64, $matches);
            $fileExtension = $matches[1] ?? 'jpeg';
            $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageBase64));
            $imagesDir = dirname(__DIR__, 1) . '/Images/';
            $filePath = $imagesDir . $imageName . '.' . $fileExtension;
            file_put_contents($filePath, $decodedImage);
            $this->result[] = [
                'image_name' => $imageName,
                'link' => $imageLink,
                'file_path' => $filePath,
                'name' => $productData['product_name']['name'],
            ];

        }
    }
}