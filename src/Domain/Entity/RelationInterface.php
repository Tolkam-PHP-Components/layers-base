<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Entity;

interface RelationInterface
{
    /**
     * @param array $source
     *
     * @return static
     */
    public static function fromArray(array $source): static;
}
