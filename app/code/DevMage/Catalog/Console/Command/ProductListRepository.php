<?php

declare(strict_types=1);

namespace DevMage\Catalog\Console\Command;

use Exception;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProductListRepository extends Command
{
    /**
     * @param ProductRepository $productRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param State $appState
     */
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly SearchCriteriaBuilder $searchCriteriaBuilder,
        private readonly State $appState
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('custom:product-repository:handler')
            ->setDescription('Update description for active products using repository.');
        parent::configure();
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
        $processedCount = 0;

        $items = $this->getProducts();

        if (empty($items)) {
            $output->writeln('<comment>No active products found.</comment>');
            return Cli::RETURN_SUCCESS;
        }

        foreach ($items as $product) {
            $randomDesc = $this->generateRandomDescription();
            $product->setDescription($randomDesc);

            try {
                $this->productRepository->save($product);
                $output->writeln(sprintf(
                    '<info>Updated SKU:</info> %s | <info>Description:</info> %s',
                    $product->getSku(),
                    $randomDesc
                ));
                $processedCount++;
            } catch (Exception $e) {
                $output->writeln("<error>Failed to save product {$product->getSku()}: {$e->getMessage()}</error>");
            }

            unset($product);
        }

        $duration = round(microtime(true) - $start, 4);
        $memory = round(memory_get_peak_usage(true) / 1024 / 1024, 2);

        $output->writeln("<info>Processed products:</info> $processedCount");
        $output->writeln("<info>Execution time:</info> {$duration} seconds");
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

            return $this->productRepository->getList($searchCriteria)->getItems();
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
            'Best quality guaranteed.',
            'Limited time offer!',
            'New arrival — don\'t miss it.',
            'Top-rated product.',
            'Customer favorite.',
            'Now with improved design!',
        ];

        return $phrases[array_rand($phrases)];
    }
}
