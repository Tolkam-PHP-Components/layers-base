<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Entity;

interface RelationInterface
{
    /**
     * @param array $source
     *
     * @return RelationInterface
     */
    public static function fromArray(array $source);
}
