<?php

namespace Lw\Infrastructure\Domain\Model\User;

use Lw\Domain\Model\User\UserFactory;
use Lw\Domain\Model\User\UserId;

class DoctrineUserFactory implements UserFactory
{
    /**
     * @param UserId $userId
     * @param $email
     * @param $password
     * @return mixed
     */
    public function build(UserId $userId, $email, $password)
    {
        return new DoctrineUser(
            $userId,
            $email,
            $password
        );
    }
}
