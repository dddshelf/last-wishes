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
        $mostRecentId = $connection->fetchAll(
            'SELECT most_recent_published_message_id FROM event_published_message_tracker WHERE type_name = ?',
            [$aTypeName]
        );

        if (!$mostRecentId) {
            return null;
        }

        return $mostRecentId[0];
    }
}
