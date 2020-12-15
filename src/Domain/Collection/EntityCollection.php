<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Collection;

use Tolkam\Base\Domain\Entity\IdentifiableEntityInterface;
use Tolkam\Base\Domain\Entity\SnapshotInterface;

abstract class EntityCollection extends Collection
{
    /**
     * Creates collection from snapshots
     *
     * @param callable                $entityCreator
     * @param SnapshotCollection|null $snapshots
     * @param bool                    $keyById
     *
     * @return static
     */
    public static function fromSnapshots(
        callable $entityCreator,
        SnapshotCollection $snapshots = null,
        bool $keyById = true
    ): static {
        if (!$snapshots) {
            return static::empty();
        }
        
        return static::create(static function (self $self) use (
            $snapshots,
            $entityCreator,
            $keyById
        ) {
            /** @var SnapshotInterface $snapshot */
            foreach ($snapshots as $k => $snapshot) {
                $key = $k;
                $entity = $entityCreator($snapshot);
                if ($keyById && $entity instanceof IdentifiableEntityInterface) {
                    $key = (string) $entity->getId();
                }
                
                yield $key => $entity;
            }
            
            if ($paginationResult = $snapshots->getPaginationResult()) {
                $self->setPaginationResult($paginationResult);
            }
        });
    }
}
