<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Collection;

use Tolkam\Base\Domain\Value\ValueInterface;

abstract class RelationCollection extends Collection
{
    /**
     * @inheritDoc
     */
    public function toArray(callable $callback = null): array
    {
        return parent::toArray(fn($v) => $v instanceof ValueInterface ? $v->value() : $v);
    }
}
