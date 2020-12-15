<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Entity;

interface EntityInterface
{
    /**
     * Exports snapshot
     *
     * @return SnapshotInterface
     */
    public function toSnapshot();
}
