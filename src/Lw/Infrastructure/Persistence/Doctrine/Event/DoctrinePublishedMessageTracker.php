<?php

namespace Lw\Infrastructure\Persistence\Doctrine\Event;

use Doctrine\ORM\EntityRepository;

class DoctrinePublishedMessageTracker extends EntityRepository implements \Lw\Domain\PublishedMessageTracker
{
    /**
     * @param string $typeName
     * @return int
     */
    public function mostRecentPublishedMessageId($typeName)
    {
        $queryBuilder = $this->createQueryBuilder('pmt');
        $queryBuilder
            ->from('Lw\\Domain\\')
            ->where()


        $query = $this->createQueryBuilder('pmt');
        $query->andWhere('pmt.typeName = ?', $typeName);
        return $queryBuilder->getQuery()->getResult();
    }
}
