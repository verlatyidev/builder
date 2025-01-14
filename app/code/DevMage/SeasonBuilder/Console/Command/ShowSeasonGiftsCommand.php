<?php

declare(strict_types=1);

namespace DevMage\SeasonBuilder\Console\Command;

use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use DevMage\SeasonBuilder\Model\Builder\WinterGiftSetBuilder;
use DevMage\SeasonBuilder\Model\Builder\SpringGiftSetBuilder;
use DevMage\SeasonBuilder\Model\Builder\SummerGiftSetBuilder;
use DevMage\SeasonBuilder\Model\Builder\AutumnGiftSetBuilder;
use DevMage\SeasonBuilder\Model\Director\SeasonGiftDirector;

class ShowSeasonGiftsCommand extends Command
{
    /**
     * @param SeasonGiftDirector $director
     * @param WinterGiftSetBuilder $winterBuilder
     * @param SpringGiftSetBuilder $springBuilder
     * @param SummerGiftSetBuilder $summerBuilder
     * @param AutumnGiftSetBuilder $autumnBuilder
     */
    public function __construct(
        private readonly SeasonGiftDirector $director,
        private readonly WinterGiftSetBuilder $winterBuilder,
        private readonly SpringGiftSetBuilder $springBuilder,
        private readonly SummerGiftSetBuilder $summerBuilder,
        private readonly AutumnGiftSetBuilder $autumnBuilder
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('season:set:show')
            ->setDescription('Show all seasonal gift sets (Builder pattern demo)');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // List of builders (winter, spring, summer, autumn)
        $builders = [
            $this->winterBuilder,
            $this->springBuilder,
            $this->summerBuilder,
            $this->autumnBuilder
        ];

        foreach ($builders as $builder) {
            //"Director" builds the proposition / product
            $dto = $this->director->buildSeasonGift($builder);

            $output->writeln('<info>--- ' . $dto->getSeasonName() . ' ---</info>');
            $output->writeln('Color: ' . $dto->getColor());
            $output->writeln('Slogan: ' . $dto->getSlogan());
            $output->writeln('Filter params: ');

            foreach ($dto->getFilterParams() as $filter) {
                $output->writeln('   - ' . $filter['field'] . ' = ' . $filter['value']);
            }

            $output->writeln('Product IDs found: [' . implode(', ', $dto->getProductIds()) . ']');
            $output->writeln('');
        }

        return Cli::RETURN_SUCCESS;
    }
}
