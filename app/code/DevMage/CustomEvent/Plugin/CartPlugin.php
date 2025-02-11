<?php

declare(strict_types=1);

namespace DevMage\CustomEvent\Plugin;

use DevMage\CustomEvent\Model\CartHandler;
use Magento\Checkout\Model\Cart;
use Psr\Log\LoggerInterface;

class CartPlugin
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
     * @param Cart $subject
     * @param Cart $result
     * @param $productInfo
     * @param $requestInfo
     * @return Cart
     */
    public function afterAddProduct(Cart $subject, Cart $result, $productInfo, $requestInfo = null): Cart
    {
        $startTime = microtime(true);

        if (!$productInfo) {
            $this->logger->error('CartPlugin Error: No product found in cart.');
            return $result;
        }

        $addedSku = $productInfo->getSku();
        $this->logger->info('CartPlugin: Product added to cart', ['sku' => $addedSku]);

        $products = $this->cartHandler->getProducts();

        if (!$products) {
            $this->logger->info('CartPlugin: No products found in cart.');
            return $result;
        }

        $productCount = count($products);
        $this->logger->info('CartPlugin: Loaded Products Count: ' . $productCount);

        // this approach is not very good, but it is suitable for our task of testing and comparing solutions
        $foundProduct = null;
        foreach ($products as $product) {
            if ($product->getSku() === $addedSku) {
                $foundProduct = $product;
                break;
            }
        }

        if ($foundProduct) {
            $this->logger->info('CartPlugin: Product Found in Cart', [
                'sku' => $foundProduct->getSku(),
                'id' => $foundProduct->getId(),
                'name' => $foundProduct->getName(),
            ]);
        } else {
            $this->logger->warning('CartPlugin: Product NOT found in cart', ['sku' => $addedSku]);
        }

        $executionTime = microtime(true) - $startTime;
        $this->logger->info('CartPlugin Execution Time: ' . $executionTime . ' sec');

        return $result;
    }
}
