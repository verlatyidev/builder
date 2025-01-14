<?php

declare(strict_types=1);

namespace DevMage\SeasonBuilder\Model\Director;

use DevMage\SeasonBuilder\Model\Builder\SeasonGiftBuilderInterface;
use DevMage\SeasonBuilder\Model\Builder\Dto\SeasonGiftDto;

/**
 *
 * Director manages the build "steps":
 * 1) reset()
 * 2) setSeasonName()
 * 3) setColor()
 * 4) setSlogan()
 * 5) setFilterParams()
 * 6) getResult()
 *
 */
class SeasonGiftDirector
{
    /**
     *
     * A function that accepts a specific builder,
     * calls methods in the required order and returns the finished result.
     *
     */
    public function buildSeasonGift(SeasonGiftBuilderInterface $builder): SeasonGiftDto
    {
        $builder->reset();
        $builder->setSeasonName();
        $builder->setColor();
        $builder->setSlogan();
        $builder->setFilterParams();

        return $builder->getResult();
    }
}
