<?php

namespace Lw\Domain;

interface StoredEventRepository
{
    /**
     * @param \Lw\Domain\Model\Event\StoredEvent $event
     */
    public function append($event);
}
