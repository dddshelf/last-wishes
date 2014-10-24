<?php

namespace Lw\Domain;

interface PublishedMessageTracker
{
    /**
     * @param string $aTypeName
     * @return int
     */
    public function mostRecentPublishedMessageId($aTypeName);

    /**
     * @param $aTypeName
     * @param StoredEvent[] $notifications
     */
    public function trackMostRecentPublishedMessage($aTypeName, $notifications);
}
