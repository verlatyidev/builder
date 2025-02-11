<?php

declare(strict_types=1);

namespace DevMage\CustomEvent\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class CartHandler
{
    /**
     * @param ProductRepositoryInterface $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
    }

    /**
     * @return array|null
     */
    public function getProducts(): ?array
    {
        /* for test two times load collection */
        for ($i = 0; $i < 2; $i++) {
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $products = $this->productRepository->getList($searchCriteria)?->getItems();
        }

        return $products;
    }
}
