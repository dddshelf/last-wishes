<?php

namespace Lw\Infrastructure\Persistence\Doctrine\User;

use Doctrine\ORM\EntityRepository;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\Wish;
use Lw\Domain\Model\Wish\WishRepository;

class DoctrineWishRepository extends EntityRepository implements WishRepository
{
    /**
     * @param UserId $userId
     * @return Wish[]
     */
    public function wishesOfUserId(UserId $userId)
    {
        return $this->findBy('user', $userId->id());
    }

    /**
     * @param Wish $wish
     * @return mixed
     */
    public function persist(Wish $wish)
    {
        $this->getEntityManager()->persist($wish);
        $this->getEntityManager()->flush($wish);
    }
}
