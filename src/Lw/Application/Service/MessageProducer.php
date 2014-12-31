<?php

namespace Lw\Application\Service;

interface MessageProducer
{
    /**
     * @param string $notificationMessage
     * @param string $notificationType
     * @param int $notificationId
     * @param \DateTime $notificationOccurredOn
     */
    public function send($notificationMessage, $notificationType, $notificationId, \DateTime $notificationOccurredOn);
    public function close();
}
