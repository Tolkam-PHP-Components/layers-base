<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Value\Common;

class Count extends IntegerValue
{
    /**
     * @param int $start
     *
     * @return static
     */
    public static function create(int $start = 0): static
    {
        return new static($start);
    }
    
    /**
     * Increases
     *
     * @return static
     */
    public function increased(): static
    {
        return new static($this->value + 1);
    }
    
    /**
     * Decreases
     *
     * @return static
     */
    public function decreased(): static
    {
        return new static($this->value - 1);
    }
    
    /**
     * Checks if counter reached specific limit
     *
     * @param $limit
     *
     * @return bool
     */
    public function hasReached($limit): bool
    {
        return $this->value >= (int) $limit;
    }
    
    /**
     * Resets
     *
     * @return static
     */
    public function reset(): static
    {
        return new static(0);
    }
}
