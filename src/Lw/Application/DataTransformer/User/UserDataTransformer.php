<?php

namespace Lw\Application\DataTransformer\User;

use Lw\Domain\Model\User\User;

interface UserDataTransformer
{
    public function write(User $user);

    public function read();
}
