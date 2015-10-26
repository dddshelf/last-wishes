<?php

namespace Lw\Infrastructure\Domain\Model\Wish;

use Doctrine\ORM\EntityRepository;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\Wish;
use Lw\Domain\Model\Wish\WishId;
use Lw\Domain\Model\Wish\WishRepository;

class DoctrineWishRepository extends EntityRepository implements WishRepository
{
    /**
     * @param WishId $wishId
     *
     * @return Wish
     */
    public function ofId(WishId $wishId)
    {
        return $this->find($wishId);
    }

    /**
     * @param UserId $userId
     *
     * @return Wish[]
     */
    public function ofUserId(UserId $userId)
    {
        return $this->findBy(['userId' => $userId]);
    }

    /**
     * @param Wish $wish
     *
     * @return mixed
     */
    public function add(Wish $wish)
    {
        $this->getEntityManager()->persist($wish);
        $this->getEntityManager()->flush($wish);
    }

    /**
     * @param Wish $wish
     */
    public function remove(Wish $wish)
    {
        $this->getEntityManager()->remove($wish);
        $this->getEntityManager()->flush($wish);
    }

    /**
     * @return WishId
     */
    public function nextIdentity()
    {
        return new WishId();
    }
}
