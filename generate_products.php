<?php

use Magento\Framework\App\Bootstrap;

require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode('adminhtml');

$productFactory = $objectManager->get('\Magento\Catalog\Model\ProductFactory');

for ($i = 1001; $i <= 10000; $i++) {
    $product = $productFactory->create();
    $product->setSku('test-sku-' . $i);
    $product->setName('Test Product ' . $i);
    $product->setAttributeSetId(4);
    $product->setStatus(1);
    $product->setWeight(1);
    $product->setVisibility(4);
    $product->setTaxClassId(0);
    $product->setTypeId('simple');
    $product->setPrice(10.99);
    $product->setStockData([
        'use_config_manage_stock' => 1,
        'qty' => 100,
        'is_qty_decimal' => 0,
        'is_in_stock' => 1,
    ]);
    $product->save();
}

echo "done.\n";
