<?php

declare(strict_types=1);

namespace DevMage\SeasonBuilder\Model\Builder;

class PseudoSearchCriteriaBuilder
{
    private array $filters = []; // only for imitate builder filter params!

    private bool|int $limit = false;

    /**
     * @param string $field
     * @param mixed $value
     * @param string $condition
     * @return $this
     */
    public function addFilter(string $field, mixed $value, string $condition): self
    {
        $this->filters[] = [
            'field'     => $field,
            'value'     => $value,
            'condition' => $condition
        ];

        return $this;
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function setPageSize(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Imitation, in real Magento there would be a call to the repository.
     */
    public function create(): array
    {
        $count = rand(3, 7);
        $result = [];

        for ($i = 1; $i <= $count; $i++) {
            $result[] = rand(1000, 9999);
        }

        if ($this->limit !== false) {
            $result = array_slice($result, 0, $this->limit);
        }

        return $result;
    }
}
