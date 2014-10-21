<?php

namespace Lw\Application\Service\User;

class ViewWishesRequest
{
    private $userId;

    /**
     * @param string $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function userId()
    {
        return $this->userId;
    }
}
