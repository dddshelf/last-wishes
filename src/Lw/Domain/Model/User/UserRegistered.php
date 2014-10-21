<?php

namespace Lw\Domain\Model\User;

use Lw\Domain\DomainEvent;

class UserRegistered implements DomainEvent
{
    /**
     * @var UserId
     */
    private $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function userId()
    {
        return $this->userId;
    }

    /**
     * @return \DateTime
     */
    public function occurredOn()
    {
        return new \DateTime();
    }
}
