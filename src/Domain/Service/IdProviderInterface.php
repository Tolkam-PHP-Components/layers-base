<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Service;

interface IdProviderInterface
{
    /**
     * Gets next id
     *
     * @return mixed
     */
    public function next();
}
