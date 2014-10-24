<?php

namespace Lw\Infrastructure\Persistence\Doctrine\Event;

use Doctrine\ORM\EntityRepository;
use Lw\Domain\StoredEventRepository;

class DoctrineEventRepository extends EntityRepository implements StoredEventRepository
{
    public function append($anEvent)
    {
        $this->getEntityManager()->persist($anEvent);
        $this->_em->flush($anEvent);
    }

    public function allStoredEventsSince($anEventId)
    {
        $query = $this->createQueryBuilder('e');
        if ($anEventId) {
            $query->where('e.eventId > :eventId');
            $query->setParameters(array('eventId' => $anEventId));
        }

        return $query->getQuery()->getResult();
    }
}
