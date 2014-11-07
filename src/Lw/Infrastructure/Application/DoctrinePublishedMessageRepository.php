<?php

namespace Lw\Infrastructure\Application;

use Doctrine\ORM\EntityRepository;
use Lw\Domain\PublishedMessageTracker;

class DoctrinePublishedMessageRepository extends EntityRepository implements PublishedMessageTracker
{
    /**
     * @param string $aTypeName
     * @return int
     */
    public function mostRecentPublishedMessageId($aTypeName)
    {
        $connection = $this->getEntityManager()->getConnection();
        $mostRecentId = $connection->fetchColumn(
            'SELECT most_recent_published_message_id FROM lw_event_published_message_tracker WHERE type_name = ?',
            [$aTypeName]
        );

        if (!$mostRecentId) {
            return null;
        }

        return $mostRecentId;
    }

    /**
     * @param $aTypeName
     * @param StoredEvent[] $notifications
     */
    public function trackMostRecentPublishedMessage($aTypeName, $notifications)
    {
        $maxId = array_reduce(
            $notifications, function($carry, $item) {
                return max($carry, $item->eventId());
            },
            0
        );

        $publishedMessage = $this->find($aTypeName);
        if (!$publishedMessage) {
            $publishedMessage = new PublishedMessage(
                $aTypeName,
                $maxId
            );
        }

        $publishedMessage->updateMaxId($maxId);

        $this->getEntityManager()->persist($publishedMessage);
        $this->getEntityManager()->flush($publishedMessage);
    }
}
