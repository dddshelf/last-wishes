<?php

namespace Lw\Application\DataTransformer\User;

use Lw\Domain\Model\User\User;

class UserDtoDataTransformer implements UserDataTransformer
{
    private $user;

    public function write(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function read()
    {
        return [
            'id' => $this->user->id()->id(),
            'num_wishes' => 0,
        ];
    }
}
