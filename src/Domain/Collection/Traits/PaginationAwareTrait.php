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
     * @return static
     */
    public function setPaginationResult(PaginationResultInterface $paginationResult): static
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
