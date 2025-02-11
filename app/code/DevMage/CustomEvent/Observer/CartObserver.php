<?php

declare(strict_types=1);

namespace DevMage\CustomEvent\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Psr\Log\LoggerInterface;
use DevMage\CustomEvent\Model\CartHandler;
use Magento\Catalog\Api\Data\ProductInterface;

class CartObserver implements ObserverInterface
{
    /**
     * @param LoggerInterface $logger
     * @param CartHandler $cartHandler
     */
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly CartHandler $cartHandler
    ) {
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $startTime = microtime(true);

        /** @var ProductInterface|null $addedProduct */
        $addedProduct = $observer->getEvent()?->getProduct();

        if (!$addedProduct) {
            $this->logger->error('CartObserver Error: No product found in event.');
            return;
        }

        $addedSku = $addedProduct->getSku();
        $this->logger->info('CartObserver: Product added to cart', ['sku' => $addedSku]);

        $products = $this->cartHandler->getProducts();

        if (!$products) {
            $this->logger->info('CartObserver: No products found in cart.');
            return;
        }

        $productCount = count($products);
        $this->logger->info('CartObserver: Loaded Products Count: ' . $productCount);

        // this approach is not very good, but it is suitable for our task of testing and comparing solutions
        $foundProduct = null;
        foreach ($products as $product) {
            if ($product->getSku() === $addedSku) {
                $foundProduct = $product;
                break;
            }
        }

        if ($foundProduct) {
            $this->logger->info('CartObserver: Product Found in Cart', [
                'sku' => $foundProduct->getSku(),
                'id' => $foundProduct->getId(),
                'name' => $foundProduct->getName(),
            ]);
        } else {
            $this->logger->warning('CartObserver: Product NOT found in cart', ['sku' => $addedSku]);
        }

        $executionTime = microtime(true) - $startTime;
        $this->logger->info('CartObserver Execution Time: ' . $executionTime . ' sec');
    }
}
