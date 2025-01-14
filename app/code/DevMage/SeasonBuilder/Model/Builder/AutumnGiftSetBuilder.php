<?php

declare(strict_types=1);

namespace DevMage\SeasonBuilder\Model\Builder;

class AutumnGiftSetBuilder extends AbstractSeasonGiftBuilder
{
    /**
     * @return void
     */
    public function setSeasonName(): void
    {
        $this->seasonName = 'Autumn';
    }

    /**
     * @return void
     */
    public function setColor(): void
    {
        $this->color = '#FFA500';
    }

    /**
     * @return void
     */
    public function setSlogan(): void
    {
        $this->slogan = 'Fall into savings!';
    }

    /**
     * @return void
     */
    public function setFilterParams(): void
    {
        $this->filterParams = [
            ['field' => 'category', 'value' => 'autumn'],
            ['field' => 'stock',    'value' => '>0']
        ];

        $builder = $this->pseudoSearchCriteriaBuilderFactory->create();

        $builder
            ->addFilter('category', 'autumn', 'eq')
            ->addFilter('stock', 1, 'gt')
            ->setPageSize(4);

        $this->productIds = $builder->create();
    }
}
