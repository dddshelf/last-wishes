<?php

namespace Lw\Application;

interface EventStore
{
    /**
     * @param DomainEvent $aDomainEvent
     * @return mixed
     */
    public function append($aDomainEvent);

    /**
     * @param $anEventId
     * @return DomainEvent[]
     */
    public function allStoredEventsSince($anEventId);
}
