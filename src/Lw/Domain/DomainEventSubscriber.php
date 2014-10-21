<?php

namespace Lw\Domain;

interface DomainEventSubscriber
{
    public function handle($anEvent);

    public function isSubscribedTo($aDomainEvent);
}
