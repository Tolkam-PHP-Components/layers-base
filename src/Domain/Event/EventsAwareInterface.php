<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Event;

interface EventsAwareInterface
{
    /**
     * Checks if it has pending events
     *
     * @return bool
     */
    public function hasEvents(): bool;
    
    /**
     * Gets raised events
     *
     * @return array
     */
    public function popEvents(): array;
}
