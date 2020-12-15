<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Event;

interface EventsAwareInterface
{
    /**
     * Checks if has pending events
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
