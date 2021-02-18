<?php declare(strict_types=1);

namespace Tolkam\Layers\Base\Domain\Event;

use Psr\EventDispatcher\EventDispatcherInterface;

class EventPublisher implements EventPublisherInterface
{
    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }
    
    /**
     * @inheritDoc
     */
    public function publish(DomainEventInterface $event): void
    {
        $this->eventDispatcher->dispatch($event);
    }
    
    /**
     * @inheritDoc
     */
    public function publishAll(EventsAwareInterface $source): void
    {
        foreach ($source->popEvents() as $event) {
            $this->eventDispatcher->dispatch($event);
        }
        
        // dispatch newly raised events
        if ($source->hasEvents()) {
            $this->publishAll($source);
        }
    }
}
