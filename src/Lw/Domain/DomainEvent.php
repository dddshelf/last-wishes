<?php

namespace Lw\Domain;

interface DomainEvent
{
    /**
     * @return \DateTime
     */
    public function occurredOn();
}
