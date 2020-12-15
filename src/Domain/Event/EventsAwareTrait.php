<?php declare(strict_types=1);

namespace Tolkam\Base\Domain\Event;

trait EventsAwareTrait
{
    /**
     * @var array|DomainEventInterface[]
     */
    private array $events = [];
    
    /**
     * @return bool
     */
    public function hasEvents(): bool
    {
        return !empty($this->events);
    }
    
    /**
     * @return array
     */
    public function popEvents(): array
    {
        $events = $this->events;
        
        $this->events = [];
        
        return $events;
    }
    
    /**
     * @param DomainEventInterface $event
     */
    protected function raiseEvent(DomainEventInterface $event): void
    {
        $this->events[] = $event;
    }
}
