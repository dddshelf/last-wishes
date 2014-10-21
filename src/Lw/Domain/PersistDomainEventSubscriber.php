<?php

namespace Lw\Domain;

use Lw\Domain\Model\Event\StoredEvent;

class PersistDomainEventSubscriber implements DomainEventSubscriber
{
    private $eventRepository;

    public function __construct($eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param DomainEvent $anEvent
     */
    public function handle($anEvent)
    {
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $serializedEvent = $serializer->serialize($anEvent, 'json');

        $databaseEvent = new StoredEvent(
            get_class($anEvent),
            $anEvent->occurredOn(),
            $serializedEvent
        );

        $this->eventRepository->append($databaseEvent);
    }

    public function isSubscribedTo($aDomainEvent)
    {
        return true;
    }
}
