<?php

namespace Lw\Application\Service;

use Ddd\Application\EventStore;
use JMS\Serializer\SerializerBuilder;
use Lw\Domain\Model\Event\StoredEvent;
use Lw\Domain\PublishedMessageTracker;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class NotificationService
{
    const EXCHANGE_NAME = 'lastwill.out';

    private $serializer;
    private $eventStore;
    private $publishedMessageTracker;
    private $messageProducer;

    public function __construct(
        EventStore $anEventStore,
        PublishedMessageTracker $aPublishedMessageTracker,
        MessageProducer $aMessageProducer
    )
    {
        $this->eventStore = $anEventStore;
        $this->publishedMessageTracker = $aPublishedMessageTracker;
        $this->messageProducer = $aMessageProducer;
    }

    public function publishNotifications()
    {
        $publishedMessageTracker = $this->publishedMessageTracker();
        $notifications = $this->listUnpublishedNotifications(
            $publishedMessageTracker->mostRecentPublishedMessageId()
        );

        if (!$notifications) {
            return;
        }

        $messageProducer = $this->messageProducer();
        try {
            $lastPublishedNotification = null;
            foreach ($notifications as $notification) {
                $lastPublishedNotification = $this->publish($notification, $messageProducer);
            }

        } finally {
            $this->trackMostRecentPublishedMessage($publishedMessageTracker, $lastPublishedNotification);
            $messageProducer->close();
        }
    }

    /**
     * @return PublishedMessageTracker
     */
    protected function publishedMessageTracker()
    {
        return $this->publishedMessageTracker;
    }

    /**
     * @param $mostRecentPublishedMessageId
     * @return StoredEvent[]
     */
    private function listUnpublishedNotifications($mostRecentPublishedMessageId)
    {
        $storeEvents = $this->eventStore()->allStoredEventsSince($mostRecentPublishedMessageId);

        // Vaughn Vernon converts StoredEvents into another objects: Notification
        // Why?

        return $storeEvents;
    }

    /**
     * @return EventStore
     */
    protected function eventStore()
    {
        return $this->eventStore;
    }

    private function messageProducer()
    {
        return $this->messageProducer;
    }

    private function publish(StoredEvent $notification, MessageProducer $messageProducer)
    {
        $messageProducer->send(
            $this->serializer()->serialize($notification, 'json'),
            $notification->typeName(),
            $notification->eventId(),
            $notification->occurredOn()
        );

        return $notification;
    }

    /**
     * @return \JMS\Serializer\Serializer
     */
    private function serializer()
    {
        if (null === $this->serializer) {
            $this->serializer = SerializerBuilder::create()->build();
        }

        return $this->serializer;
    }

    private function trackMostRecentPublishedMessage(PublishedMessageTracker $publishedMessageTracker, $notification)
    {
        $publishedMessageTracker->trackMostRecentPublishedMessage(self::EXCHANGE_NAME, $notification);
    }
}
