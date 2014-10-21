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

    public function handle($anEvent)
    {
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $serializedEvent = $serializer->serialize($anEvent, 'json');

        $databaseEvent = new StoredEvent(
            $anEvent::class,
            $anEvent->occuredOn(),
            $serializedEvent
        );

        $this->eventRepository->append($databaseEvent);
    }

    public function isSubscribedTo($aDomainEvent)
    {
        // TODO: Implement isSubscribedTo() method.
    }
}
