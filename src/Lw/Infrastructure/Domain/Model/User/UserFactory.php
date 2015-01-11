<?php

namespace Lw\Infrastructure\Domain\Model\User;

use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserFactory as BaseUserFactory;

class UserFactory implements BaseUserFactory
{
    /**
     * {@inheritdoc}
     */
    public function build(UserId $userId, $email, $password)
    {
        return new User($userId, $email, $password);
    }
}
