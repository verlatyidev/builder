<?php

declare(strict_types=1);

namespace DevMage\SeasonBuilder\Model\Builder;

use DevMage\SeasonBuilder\Model\Builder\Dto\SeasonGiftDtoFactory;
use DevMage\SeasonBuilder\Model\Builder\Dto\SeasonGiftDto;
use DevMage\SeasonBuilder\Model\Builder\PseudoSearchCriteriaBuilder;
use DevMage\SeasonBuilder\Model\Builder\PseudoSearchCriteriaBuilderFactory;

abstract class AbstractSeasonGiftBuilder implements SeasonGiftBuilderInterface
{
    protected string $seasonName = '';

    protected string $color = '';

    protected string $slogan = '';

    protected array  $filterParams = [];

    protected array  $productIds = [];

    /**
     * @param SeasonGiftDtoFactory $seasonGiftDtoFactory
     * @param PseudoSearchCriteriaBuilderFactory $pseudoSearchCriteriaBuilderFactory
     */
    public function __construct(
        protected readonly SeasonGiftDtoFactory $seasonGiftDtoFactory,
        protected readonly PseudoSearchCriteriaBuilderFactory $pseudoSearchCriteriaBuilderFactory
    ) {
    }

    /**
     * @return void
     */
    public function reset(): void
    {
        $this->seasonName   = '';
        $this->color        = '';
        $this->slogan       = '';
        $this->filterParams = [];
        $this->productIds   = [];
    }

    /**
     * @return SeasonGiftDto
     */
    public function getResult(): SeasonGiftDto
    {
        return $this->seasonGiftDtoFactory->create([
            'seasonName'   => $this->seasonName,
            'color'        => $this->color,
            'slogan'       => $this->slogan,
            'filterParams' => $this->filterParams,
            'productIds'   => $this->productIds
        ]);
    }
}
