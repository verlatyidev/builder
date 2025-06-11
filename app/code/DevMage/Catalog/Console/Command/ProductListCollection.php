<?php

declare(strict_types=1);

namespace DevMage\Catalog\Console\Command;

use Exception;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Framework\App\Area;

class ProductListCollection extends Command
{
    private const PAGE_SIZE = 500;

    /**
     * @param CollectionFactory $productCollectionFactory
     * @param State $appState
     */
    public function __construct(
        private readonly CollectionFactory $productCollectionFactory,
        private readonly State $appState
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
        try {
            $this->appState->setAreaCode(Area::AREA_ADMINHTML);
        } catch (LocalizedException $e) {
        }

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
                try {
                    $randomDesc = $this->generateRandomDescription();
                    $product->setDescription($randomDesc);

                    $product->getResource()->saveAttribute($product, 'description');

                    $output->writeln(sprintf(
                        '<info>Updated SKU:</info> %s | <info>Description:</info> %s',
                        $product->getSku(),
                        $randomDesc
                    ));
                    $processedCount++;
                } catch (Exception $e) {
                    $output->writeln("<error>Error on SKU {$product->getSku()}: {$e->getMessage()}</error>");
                }
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
            //$collection->addAttributeToSelect(['sku', 'description']);
            $collection->addFieldToFilter('entity_id', ['in' => $batchIds]);
            $collection->addFieldToFilter('status', 1);

            return $collection;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * @return string
     */
    private function generateRandomDescription(): string
    {
        $phrases = [
            'High quality item.',
            'Limited stock available!',
            'Customer favorite.',
            'New updated version.',
            'Great value guaranteed.',
            'Top seller this week!',
        ];

        return $phrases[array_rand($phrases)];
    }
}
