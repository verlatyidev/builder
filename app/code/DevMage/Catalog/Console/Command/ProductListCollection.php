<?php

declare(strict_types=1);

namespace DevMage\Catalog\Console\Command;

use Exception;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProductListCollection extends Command
{
    private const PAGE_SIZE = 500;

    /**
     * @param CollectionFactory $productCollectionFactory
     */
    public function __construct(
        private readonly CollectionFactory $productCollectionFactory
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('custom:product-collection:handler')
            ->setDescription('Iterate collection active products in batches with memory tracking.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = microtime(true);
        $pageSize = self::PAGE_SIZE;
        $processedCount = 0;

        /** @var Collection $idsCollection */
        $idsCollection = $this->productCollectionFactory->create();
        $idsCollection->addFieldToFilter('status', 1);
        $ids = $idsCollection->getAllIds();
        unset($idsCollection);

        $total = count($ids);
        $output->writeln("<info>Total active products:</info> $total");

        if ($total === 0) {
            $output->writeln('<comment>No active products found.</comment>');
            return Cli::RETURN_SUCCESS;
        }

        for ($offset = 0; $offset < $total; $offset += $pageSize) {
            $batchIds = array_slice($ids, $offset, $pageSize);
            if (empty($batchIds)) {
                continue;
            }

            $batchCollection = $this->getProducts($batchIds);
            if (empty($batchCollection)) {
                continue;
            }

            foreach ($batchCollection as $product) {
                $output->writeln(sprintf(
                    '<info>SKU:</info> %s | <info>Name:</info> %s',
                    $product->getSku(),
                    $product->getName()
                ));
                $processedCount++;
            }

            $batchCollection->clear();
            unset($batchCollection);
        }

        $duration = round(microtime(true) - $start, 4);
        $mem = round(memory_get_peak_usage(true) / 1024 / 1024, 2);

        $output->writeln("<info>Processed products:</info> $processedCount");
        $output->writeln("<info>Execution time:</info> {$duration} seconds");
        $output->writeln("<info>Peak memory usage:</info> {$mem} MB");

        return Cli::RETURN_SUCCESS;
    }

    /**
     * @param array $batchIds
     * @return Collection|array
     */
    private function getProducts(array $batchIds): array|Collection
    {
        try {
            $collection = $this->productCollectionFactory->create();
            $collection->addAttributeToSelect(['*']);
            $collection->addFieldToFilter('entity_id', ['in' => $batchIds]);
            $collection->addFieldToFilter('status', 1);

            return $collection;
        } catch (Exception $e) {
            return [];
        }
    }
}
