<?php

declare(strict_types=1);

namespace DevMage\SeasonBuilder\Model\Builder;

class SummerGiftSetBuilder extends AbstractSeasonGiftBuilder
{
    /**
     * @return void
     */
    public function setSeasonName(): void
    {
        $this->seasonName = 'Summer';
    }

    /**
     * @return void
     */
    public function setColor(): void
    {
        $this->color = '#FFD700';
    }

    /**
     * @return void
     */
    public function setSlogan(): void
    {
        $this->slogan = 'Catch the sunshine vibes!';
    }

    /**
     * @return void
     */
    public function setFilterParams(): void
    {
        $this->filterParams = [
            ['field' => 'category', 'value' => 'summer'],
            ['field' => 'price',    'value' => '>=150']
        ];

        $builder = $this->pseudoSearchCriteriaBuilderFactory->create();

        $builder
            ->addFilter('category', 'summer', 'eq')
            ->addFilter('price', 150, 'gteq')
            ->setPageSize(4);

        $this->productIds = $builder->create();
    }
}
