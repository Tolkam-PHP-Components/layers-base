<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Entity;

interface EntityInterface
{
    /**
     * Exports snapshot
     *
     * @return SnapshotInterface
     */
    public function toSnapshot();
}
