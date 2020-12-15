<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Event;

interface EventPublisherInterface
{
    /**
     * Publishes domain event
     *
     * @param DomainEventInterface $event
     */
    public function publish(DomainEventInterface $event): void;
    
    /**
     * Publishes aggregated events
     *
     * @param EventsAwareInterface $source
     *
     * @return void
     */
    public function publishAll(EventsAwareInterface $source): void;
}
