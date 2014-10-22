<?php

namespace Lw\Domain;

interface PublishedMessageTracker
{
    /**
     * @param string $typeName
     * @return int
     */
    public function mostRecentPublishedMessageId($typeName);
}
