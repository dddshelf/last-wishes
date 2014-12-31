<?php

namespace Lw\Infrastructure\Application\Service;

use Lw\Application\Service\MessageProducer;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMqMessageProducer extends RabbitMqMessaging implements MessageProducer
{
    /**
     * @param string $notificationMessage
     * @param string $notificationType
     * @param int $notificationId
     * @param \DateTime $notificationOccurredOn
     */
    public function send($notificationMessage, $notificationType, $notificationId, \DateTime $notificationOccurredOn)
    {
        $this->channel->basic_publish(
            new AMQPMessage(
                $notificationMessage,
                ['type' => $notificationType, 'timestamp' => $notificationOccurredOn->getTimestamp(), 'message_id' => $notificationId]
            ),
            $this->outName()
        );
    }
}
