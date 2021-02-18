<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Collection\Traits;

use Tolkam\Pagination\PaginationResultInterface;

trait PaginationAwareTrait
{
    /**
     * @var PaginationResultInterface|null
     */
    protected ?PaginationResultInterface $paginationResult = null;
    
    /**
     * Sets the paginationResult
     *
     * @param PaginationResultInterface $paginationResult
     *
     * @return self
     */
    public function setPaginationResult(PaginationResultInterface $paginationResult)
    {
        $this->paginationResult = $paginationResult;
        
        return $this;
    }
    
    /**
     * @return PaginationResultInterface|null
     */
    public function getPaginationResult(): ?PaginationResultInterface
    {
        return $this->paginationResult;
    }
}
