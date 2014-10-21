<?php

namespace Lw\Domain\Model\User;

class UserRegistered
{
    public function __construct()
    {
        \Ddd\DomainPublisher::instance()->publish(
            new UserRegistered(

            )
        );

    }
}
