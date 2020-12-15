<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Store;

use Tolkam\Base\Domain\Collection\SnapshotCollection;
use Tolkam\Base\Domain\Repository\Filters;
use Tolkam\Base\Domain\Repository\Pagination;

interface SnapshotStoreInterface
{
    /**
     * @param array $values
     *
     * @return SnapshotStoreInterface
     */
    public function getByIds(array $values): SnapshotStoreInterface;
    
    /**
     * @return SnapshotStoreInterface
     */
    public function getAll(): SnapshotStoreInterface;
    
    /**
     * @param Filters $filters
     *
     * @return SnapshotStoreInterface
     */
    public function applyFilters(Filters $filters): SnapshotStoreInterface;
    
    /**
     * Gets found results
     *
     * @param Pagination|null $pagination
     *
     * @return mixed
     */
    public function getResults(Pagination $pagination = null): SnapshotCollection;
}
