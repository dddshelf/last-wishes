<?php

namespace Lw\Domain;

class PersistDomainEventSubscriber implements DomainEventSubscriber
{
    /**
     * @var EventStore
     */
    private $eventStore;

    public function __construct($eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * @param DomainEvent $anEvent
     */
    public function handle($anEvent)
    {
        $this->eventStore->append($anEvent);
    }

    public function isSubscribedTo($aDomainEvent)
    {
        return true;
    }
}
