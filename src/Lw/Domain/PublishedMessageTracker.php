<?php

namespace Lw\Domain;

interface PublishedMessageTracker
{
    /**
     * @param string $aTypeName
     * @return int
     */
    public function mostRecentPublishedMessageId($aTypeName);
}
