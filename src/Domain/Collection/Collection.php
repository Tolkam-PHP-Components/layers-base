<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Collection;

use Tolkam\Base\Domain\Collection\Traits\PaginationAwareTrait;
use Tolkam\Collection\LazyCollection;
use Tolkam\Collection\TypedLazyCollectionTrait;

/**
 * Base class for all collections
 *
 * @package Tolkam\Base\Domain\Repository
 */
abstract class Collection extends LazyCollection
{
    use TypedLazyCollectionTrait;
    use PaginationAwareTrait;
    
    /**
     * @param $name
     *
     * @return mixed
     */
    public function __get($name): mixed
    {
        return $this->get($name);
    }
    
    /**
     * @param $prop
     *
     * @return bool
     */
    public function __isset($prop): bool
    {
        return $this->get($prop) !== null;
    }
    
    /**
     * New instances available only through create() method
     *
     * @param                 $source
     * @param bool            $useCache
     * @param Collection|null $previous
     */
    final protected function __construct(
        $source,
        bool $useCache = false,
        self $previous = null
    ) {
        // collections are always cached
        parent::__construct($source, true, $previous);
        
        // set pagination results from previous instance
        if ($previous && ($paginationResult = $previous->getPaginationResult())) {
            $this->setPaginationResult($paginationResult);
        }
    }
}
