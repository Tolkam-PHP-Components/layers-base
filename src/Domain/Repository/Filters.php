<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Repository;

use IteratorAggregate;
use SplQueue;

/**
 * Typed filters bag
 */
class Filters implements IteratorAggregate
{
    /**
     * @var SplQueue
     */
    protected SplQueue $filters;
    
    private function __construct()
    {
        $this->filters = new SplQueue;
    }
    
    /**
     * @param Filter ...$filters
     *
     * @return static
     */
    public static function make(Filter ...$filters): static
    {
        $instance = new static;
        
        foreach ($filters as $filter) {
            $instance->append($filter);
        }
        
        return $instance;
    }
    
    /**
     * @param Filter $filter
     *
     * @return $this
     */
    public function prepend(Filter $filter): self
    {
        $this->filters->unshift($filter);
        
        return $this;
    }
    
    /**
     * @param Filter $filter
     *
     * @return $this
     */
    public function append(Filter $filter): self
    {
        $this->filters->push($filter);
        
        return $this;
    }
    
    /**
     * @inheritDoc
     */
    public function getIterator(): SplQueue
    {
        return $this->filters;
    }
}
