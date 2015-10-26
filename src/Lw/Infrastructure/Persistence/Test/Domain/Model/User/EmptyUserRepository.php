<?php

namespace Lw\Infrastructure\Persistence\Test\Domain\Model\User;

use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserRepository;

class EmptyUserRepository implements UserRepository
{
    /**
     * @param UserId $userId
     *
     * @return User
     */
    public function ofId(UserId $userId)
    {
        return;
    }

    /**
     * @param $email
     *
     * @return User
     */
    public function ofEmail($email)
    {
        return;
    }

    /**
     * @param User $user
     */
    public function add(User $user)
    {
    }

    /**
     * @return UserId
     */
    public function nextIdentity()
    {
        return new UserId();
    }
}
