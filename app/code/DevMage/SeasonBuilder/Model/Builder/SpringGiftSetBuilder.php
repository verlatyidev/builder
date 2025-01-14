<?php

declare(strict_types=1);

namespace DevMage\SeasonBuilder\Model\Builder;

use DevMage\SeasonBuilder\Model\Builder\Dto\SeasonGiftDtoFactory;
use DevMage\SeasonBuilder\Model\Builder\PseudoSearchCriteriaBuilderFactory;

class SpringGiftSetBuilder extends AbstractSeasonGiftBuilder
{
    /**
     * @return void
     */
    public function setSeasonName(): void
    {
        $this->seasonName = 'Spring';
    }

    /**
     * @return void
     */
    public function setColor(): void
    {
        $this->color = '#8FBC8F';
    }

    /**
     * @return void
     */
    public function setSlogan(): void
    {
        $this->slogan = 'Bloom into freshness!';
    }

    /**
     * @return void
     */
    public function setFilterParams(): void
    {
        $this->filterParams = [
            ['field' => 'category', 'value' => 'spring'],
            ['field' => 'price',    'value' => '>50']
        ];

        $builder = $this->pseudoSearchCriteriaBuilderFactory->create();

        $builder
            ->addFilter('category', 'spring', 'eq')
            ->addFilter('price', 50, 'gt')
            ->setPageSize(4);

        $this->productIds = $builder->create();
    }
}
