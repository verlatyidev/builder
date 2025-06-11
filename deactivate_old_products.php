<?php

use Magento\Framework\App\Bootstrap;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;

require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$state = $objectManager->get(\Magento\Framework\App\State::class);
$state->setAreaCode('adminhtml');

// oldest then 7 days for test
/** @var CollectionFactory $collectionFactory */
$collectionFactory = $objectManager->get(CollectionFactory::class);
$collection = $collectionFactory->create();

$collection->addAttributeToSelect(['status', 'created_at']);
$collection->addFieldToFilter('created_at', ['lt' => (new DateTime('-7 days'))->format('Y-m-d H:i:s')]);
$collection->addFieldToFilter('status', ['eq' => 1]);

$count = 0;

foreach ($collection as $product) {
    $product->setStatus(0);
    $product->getResource()->saveAttribute($product, 'status');
    $count++;
    unset($product);
}

echo "products: {$count}\n";
