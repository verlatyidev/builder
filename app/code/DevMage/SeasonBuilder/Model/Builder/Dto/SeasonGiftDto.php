<?php

declare(strict_types=1);

namespace DevMage\SeasonBuilder\Model\Builder\Dto;

class SeasonGiftDto
{
    /**
     * @param string $seasonName
     * @param string $color
     * @param string $slogan
     * @param array $filterParams
     * @param array $productIds
     */
    public function __construct(
        private readonly string $seasonName,
        private readonly string $color,
        private readonly string $slogan,
        private readonly array  $filterParams,
        private readonly array  $productIds
    ) {
    }

    /**
     * @return string
     */
    public function getSeasonName(): string
    {
        return $this->seasonName;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->color;
    }

    /**
     * @return string
     */
    public function getSlogan(): string
    {
        return $this->slogan;
    }

    /**
     * @return array
     */
    public function getFilterParams(): array
    {
        return $this->filterParams;
    }

    /**
     * @return array
     */
    public function getProductIds(): array
    {
        return $this->productIds;
    }
}
