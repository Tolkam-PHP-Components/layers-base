<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Value;

use Tolkam\Layers\Base\Domain\Entity\EntityInterface;

interface ValueInterceptorInterface
{
    /**
     * @param ValueInterface  $value
     * @param EntityInterface $parent
     *
     * @return ValueInterface
     */
    public function intercept(ValueInterface $value, EntityInterface $parent): ValueInterface;
}
