<?php

namespace Lw\Application\Service\User;

use Lw\Domain\Model\User\UserId;

class ViewBadgesRequest
{
    private $userId;

    /**
     * @param UserId $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return UserId
     */
    public function userId()
    {
        return $this->userId;
    }
}
