<?php

namespace Lw\Domain\Model\User;

interface UserService
{
    public function badgesFrom(UserId $userId);
}
