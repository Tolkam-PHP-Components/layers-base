<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Entity;

interface SnapshotInterface
{
    /**
     * Restores entity from array representation
     *
     * @param array $arr
     *
     * @return static
     */
    public static function fromArray(array $arr): static;
    
    /**
     * Exports to array
     *
     * @param bool $cast Cast values to scalars
     *
     * @return array
     */
    public function toArray(bool $cast = true): array;
}
