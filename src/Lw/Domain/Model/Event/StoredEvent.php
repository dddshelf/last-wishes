<?php

namespace Lw\Domain\Model\Event;

class StoredEvent
{
    /**
     * @var int
     */
    private $eventId;

    /**
     * @var string
     */
    private $eventBody;

    /**
     * @var \DateTime
     */
    private $occurredOn;

    /**
     * @var string
     */
    private $typeName;

    /**
     * @param string $aTypeName
     * @param \DateTime $anOccurredOn
     * @param string $anEventBody
     */
    public function __construct($aTypeName, \DateTime $anOccurredOn, $anEventBody)
    {
        $this->eventBody = $anEventBody;
        $this->typeName = $aTypeName;
        $this->occurredOn = $anOccurredOn;
    }

    public function eventBody()
    {
        return $this->eventBody;
    }

    public function eventId()
    {
        return $this->eventId;
    }
}
