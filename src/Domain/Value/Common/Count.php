<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Value\Common;

class Count extends IntegerValue
{
    /**
     * @param int $start
     *
     * @return static
     */
    public static function create(int $start = 0)
    {
        return new static($start);
    }
    
    /**
     * Increases
     *
     * @return $this
     */
    public function increased()
    {
        return new static($this->value + 1);
    }
    
    /**
     * Decreases
     *
     * @return $this
     */
    public function decreased()
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
    public function hasReached($limit)
    {
        return $this->value >= (int) $limit;
    }
    
    /**
     * Resets
     *
     * @return $this
     */
    public function reset()
    {
        return new static(0);
    }
}
