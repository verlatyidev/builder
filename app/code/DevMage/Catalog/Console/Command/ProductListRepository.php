<?php

declare(strict_types=1);

namespace DevMage\Catalog\Console\Command;

use Exception;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProductListRepository extends Command
{
    /**
     * @param ProductRepository $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('custom:product-repository:handler')
            ->setDescription('Iterate product repository list with memory tracking.');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $start = microtime(true);
        $processedCount = 0;

        $items = $this->getProducts();

        if (empty($items)) {
            $output->writeln('<comment>No active products found.</comment>');
            return Cli::RETURN_SUCCESS;
        }

        foreach ($items as $product) {
            $output->writeln(sprintf(
                '<info>SKU:</info> %s | <info>Name:</info> %s',
                $product->getSku(),
                $product->getName()
            ));
            $processedCount++;
        }
        $end = microtime(true);
        $duration = round($end - $start, 4);

        $output->writeln("<info>Processed products:</info> $processedCount");
        $output->writeln("<info>Execution time:</info> {$duration} seconds");
        $memory = round(memory_get_peak_usage(true) / 1024 / 1024, 2);
        $output->writeln("<info>Peak memory usage:</info> {$memory} MB");

        return Cli::RETURN_SUCCESS;
    }

    /**
     * @return array
     */
    private function getProducts(): array
    {
        try {
            $this->searchCriteriaBuilder->addFilter('status', 1);
            $searchCriteria = $this->searchCriteriaBuilder->create();
            $productList = $this->productRepository->getList($searchCriteria);
            return $productList->getItems();
        } catch (Exception $e) {
            return [];
        }
    }
}
