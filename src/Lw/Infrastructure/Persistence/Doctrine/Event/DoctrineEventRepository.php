<?php

namespace Lw\Infrastructure\Persistence\Doctrine\Event;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository // implements \Lw\Domain\EventRepository
{
    /**
     * @param \Lw\Domain\Model\Event\Event $event
     */
    public function persist($event)
    {
        $this->getEntityManager()->persist($event);
        $this->_em->flush($event);
    }
}
