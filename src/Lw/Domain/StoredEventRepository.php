<?php

namespace Lw\Domain;

interface StoredEventRepository
{
    /**
     * @param \Lw\Domain\Model\Event\StoredEvent $anEvent
     */
    public function append($anEvent);

    /**
     * @param $anEventId
     * @return StoredEvent[]
     */
    public function allStoredEventsSince($anEventId);
}
