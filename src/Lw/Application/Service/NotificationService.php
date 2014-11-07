<?php

namespace Lw\Application\Service;

use JMS\Serializer\SerializerBuilder;
use Lw\Domain\PublishedMessageTracker;
use Lw\Domain\EventStore;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class NotificationService
{
    const EXCHANGE_NAME = 'lastwill';
    const USE_FANOUT = false;

    private $entityManager;
    private $serializer;

    public function __construct($anEntityManager)
    {
        $this->entityManager = $anEntityManager;
    }

    public function publishNotifications()
    {
        $publishedMessageTracker = $this->publishedMessageTracker();

        $notifications = $this->listUnpublishedNotifications(
            $publishedMessageTracker->mostRecentPublishedMessageId(self::EXCHANGE_NAME)
        );

        if (!$notifications) {
            return;
        }

        $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        try {
            if (self::USE_FANOUT) {
                $channel->exchange_declare(self::EXCHANGE_NAME, 'fanout', false, false, false);
            } else {
                $channel->queue_declare(self::EXCHANGE_NAME, false, false, false, false);
            }

            $this->publishNotification($notifications, $channel);
            $publishedMessageTracker->trackMostRecentPublishedMessage(
                self::EXCHANGE_NAME, $notifications
            );
        } catch(\Exception $e) {
        } finally {
            $channel->close();
            $connection->close();
        }
    }

    /**
     * @return PublishedMessageTracker
     */
    private function publishedMessageTracker()
    {
        return $this->entityManager->getRepository('Lw\\Infrastructure\\Application\\PublishedMessage');
    }

    private function listUnpublishedNotifications($mostRecentPublishedMessageId)
    {
        return $this->eventStore()->allStoredEventsSince($mostRecentPublishedMessageId);
    }

    /**
     * @return EventStore
     */
    private function eventStore()
    {
        return $this->entityManager->getRepository('Lw\\Domain\\Model\\Event\\StoredEvent');
    }

    /**
     * @param $notifications
     * @param $channel
     */
    private function publishNotification($notifications, $channel)
    {
        foreach ($notifications as $notification) {
            $msg = new AMQPMessage($this->serializer()->serialize($notification, 'json'));

            if (self::USE_FANOUT) {
                $channel->basic_publish($msg, self::EXCHANGE_NAME);
            } else {
                $channel->basic_publish($msg, '', self::EXCHANGE_NAME);
            }
        }
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
}
