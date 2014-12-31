<?php

namespace Lw\Domain;

interface PublishedMessageTracker
{
    const EXCHANGE_NAME = 'lastwill.out';

    /**
     * @return int
     */
    public function mostRecentPublishedMessageId();

    /**
     * @param $aTypeName
     * @param StoredEvent $notification
     */
    public function trackMostRecentPublishedMessage($aTypeName, $notification);
}
