<?php declare(strict_types=1);

namespace Tolkam\Base\Application\Presentation;

interface PresentationFactoryInterface
{
    /**
     * Makes presentation instance from string id
     *
     * @param string $id
     *
     * @return callable
     */
    public function make(string $id): callable;
}
