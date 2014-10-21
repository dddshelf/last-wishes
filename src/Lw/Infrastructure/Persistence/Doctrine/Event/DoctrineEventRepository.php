<?php

namespace Lw\Infrastructure\Persistence\Doctrine\Event;

use Doctrine\ORM\EntityRepository;

class DoctrineEventRepository extends EntityRepository implements \Lw\Domain\StoredEventRepository
{
    /**
     * @param \Lw\Domain\Model\Event\StoredEvent $event
     */
    public function append($event)
    {
        $this->getEntityManager()->persist($event);
        $this->_em->flush($event);
    }
}
