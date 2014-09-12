<?php

namespace Cyoa\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityRepository;

class EventRepository extends EntityRepository// implements \Cyoa\Domain\EventRepository
{
    /**
     * @param  \Cyoa\Domain\Event $event
     */
    public function persist($event)
    {
        $this->_em->persist($event);
        $this->_em->flush($event);
    }
}
