<?php

namespace Lw\Infrastructure\Application;

use Doctrine\ORM\EntityRepository;
use Lw\Domain\Model\Event\StoredEvent;
use Lw\Domain\PublishedMessageTracker;

class DoctrinePublishedMessageRepository extends EntityRepository implements PublishedMessageTracker
{
    /**
     * @return int
     */
    public function mostRecentPublishedMessageId()
    {
        $connection = $this->getEntityManager()->getConnection();
        $mostRecentId = $connection->fetchColumn(
            'SELECT most_recent_published_message_id FROM lw_event_published_message_tracker WHERE type_name = ?',
            ['lastwill.out']
        );

        if (!$mostRecentId) {
            return null;
        }

        return $mostRecentId;
    }

    /**
     * @param $aTypeName
     * @param StoredEvent $notification
     */
    public function trackMostRecentPublishedMessage($aTypeName, $notification)
    {
        if (!$notification) {
            return;
        }

        $maxId = $notification->eventId();

        $publishedMessage = $this->find($aTypeName);
        if (!$publishedMessage) {
            $publishedMessage = new PublishedMessage(
                $aTypeName,
                $maxId
            );
        }

        $publishedMessage->updateMostRecentPublishedMessageId($maxId);

        $this->getEntityManager()->persist($publishedMessage);
        $this->getEntityManager()->flush($publishedMessage);
    }
}
