<?php

namespace Lw\Domain\Model\User;

use Ddd\Domain\DomainEvent;
use Ddd\Domain\Event\PublishableDomainEvent;

class UserRegistered implements DomainEvent, PublishableDomainEvent
{
    /**
     * @var UserId
     */
    private $userId;
    private $occurredOn;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
        $this->occurredOn = new \DateTimeImmutable();
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
