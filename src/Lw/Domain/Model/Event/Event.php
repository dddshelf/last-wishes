<?php

namespace Lw\Domain\Model\Event;

class Event
{
    private $id;
    private $eventType;
    private $eventBody;
    private $streamName;
    private $streamVersion = 1;

    public function __construct($eventType, $eventBody, $streamName)
    {
        $this->eventType = $eventType;
        $this->eventBody = $eventBody;
        $this->streamName = $streamName;
    }
}
