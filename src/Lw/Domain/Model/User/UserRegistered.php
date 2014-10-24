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
        $this->occurredOn = new \DateTime();
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
        return $this->occurredOn;
    }
}
