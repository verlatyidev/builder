<?php

declare(strict_types=1);

namespace DevMage\SeasonBuilder\Model\Builder;

class WinterGiftSetBuilder extends AbstractSeasonGiftBuilder
{
    /**
     * @return void
     */
    public function setSeasonName(): void
    {
        $this->seasonName = 'Winter';
    }

    /**
     * @return void
     */
    public function setColor(): void
    {
        $this->color = '#FFFFFF';
    }

    /**
     * @return void
     */
    public function setSlogan(): void
    {
        $this->slogan = 'Stay cozy this winter!';
    }

    /**
     * @return void
     */
    public function setFilterParams(): void
    {
        $this->filterParams = [
            ['field' => 'category', 'value' => 'winter'],
            ['field' => 'price',    'value' => '>=100']
        ];

        $builder = $this->pseudoSearchCriteriaBuilderFactory->create();

        $builder
            ->addFilter('category', 'winter', 'eq')
            ->addFilter('price', 100, 'gteq')
            ->setPageSize(5);

        $this->productIds = $builder->create();
    }
}
