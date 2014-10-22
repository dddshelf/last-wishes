<?php

namespace Lw\Application\Service;

use Lw\Domain\Model\Event\StoredEvent;
use Lw\Domain\PublishedMessageTracker;
use Lw\Domain\StoredEventRepository;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class NotificationService
{
    const EXCHANGE_NAME = 'lastwill';

    private $entityManager;

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

        var_dump($notifications);
        $this->sendNotifications($notifications);

        // $messageProducer = $this->messageProducer();
        /*
        try {
            foreach($notifications as $notification) {
                $this->publish($notification, $messageProducer);
            }
        } catch(\Exception $e) {
            $messageProducer->close();
        }
        */
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
     * @return StoredEventRepository
     */
    private function eventStore()
    {
        return $this->entityManager->getRepository('Lw\\Domain\\Model\\Event\\StoredEvent');
    }

    private function messageProducer()
    {
    }

    /**
     * @param StoredEvent[] $notifications
     */
    private function sendNotifications($notifications)
    {
        $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();

        try {
            $channel->queue_declare('lastwill.output', false, false, false, false);
            foreach($notifications as $notification) {
                $msg = new AMQPMessage($notification->eventBody());
                $channel->basic_publish($msg, '', self::EXCHANGE_NAME);
            }
        } catch(\Exception $e) {
        } finally {
            $channel->close();
            $connection->close();
        }
    }
}
