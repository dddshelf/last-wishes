<?php

namespace Lw\Domain\Model\User;

use Ddd\Domain\DomainEvent;

class LogInAttempted implements DomainEvent
{
    private $email;
    private $occurredOn;

    public function __construct($email)
    {
        $this->email = $email;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function email()
    {
        return $this->email;
    }

    /**
     * @return \DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
