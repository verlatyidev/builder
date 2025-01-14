<?php

declare(strict_types=1);

namespace DevMage\SeasonBuilder\Model\Builder;

use DevMage\SeasonBuilder\Model\Builder\Dto\SeasonGiftDto;

interface SeasonGiftBuilderInterface
{
    /**
     * for "cleaning" before a new build.
     */
    public function reset(): void;

    public function setSeasonName(): void;

    public function setColor(): void;

    public function setSlogan(): void;

    public function setFilterParams(): void;

    public function getResult(): SeasonGiftDto;
}
