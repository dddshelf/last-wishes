<?php

namespace Lw\Infrastructure\Domain\Model\User;

use Doctrine\ORM\EntityRepository;
use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserRepository;

class DoctrineUserRepository extends EntityRepository implements UserRepository
{
    /**
     * @param UserId $userId
     *
     * @return User
     */
    public function ofId(UserId $userId)
    {
        return $this->find($userId);
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function ofEmail($email)
    {
        return $this->findOneBy(['email' => $email]);
    }

    /**
     * @param User $user
     */
    public function add(User $user)
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
