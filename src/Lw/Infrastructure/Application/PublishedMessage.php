<?php

namespace Lw\Infrastructure\Application;

class PublishedMessage
{
    /**
     * @var int
     */
    private $mostRecentPublishedMessageId;

    /**
     * @var int
     */
    private $trackerId;

    /**
     * @var string
     */
    private $typeName;

    public function __construct($aMostRecentPublishedMessageId, $aTrackerId, $aTypeName)
    {
        $this->mostRecentPublishedMessageId = $aMostRecentPublishedMessageId;
        $this->trackerId = $aTrackerId;
        $this->typeName = $aTypeName;
    }

    public function mostRecentPublishedMessageId()
    {
        return $this->mostRecentPublishedMessageId;
    }
}
