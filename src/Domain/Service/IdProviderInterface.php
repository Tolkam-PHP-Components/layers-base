<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Service;

interface IdProviderInterface
{
    /**
     * Gets next id
     *
     * @return mixed
     */
    public function next(): mixed;
}
