<?php

namespace Lw\Infrastructure\Persistence\Doctrine\User;

use Doctrine\ORM\EntityRepository;
use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserRepository;

class DoctrineUserRepository extends EntityRepository implements UserRepository
{
    /**
     * @param UserId $userId
     * @return User
     */
    public function userOfId(UserId $userId)
    {
        return $this->find($userId->id());
    }

    /**
     * @param string $email
     * @return User
     */
    public function userOfEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @param User $user
     */
    public function persist(User $user)
    {
        $this->getEntityManager()->persist($user);
    }

    /**
     * @return UserId
     */
    public function nextIdentity()
    {
        return new UserId();
    }
}
