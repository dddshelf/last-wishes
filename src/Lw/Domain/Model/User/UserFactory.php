<?php

namespace Lw\Domain\Model\User;

interface UserFactory
{
    /**
     * @param UserId $userId
     * @param $email
     * @param $password
     *
     * @return mixed
     */
    public function build(UserId $userId, $email, $password);
}
