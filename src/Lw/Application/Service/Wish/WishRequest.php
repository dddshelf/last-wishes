<?php

namespace Lw\Application\Service\Wish;

class WishRequest
{
    protected $wishId;
    protected $userId;

    /**
     * @param string $wishId
     * @param string $userId
     */
    public function __construct($wishId, $userId)
    {
        $this->wishId = $wishId;
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function wishId()
    {
        return $this->wishId;
    }

    /**
     * @return string
     */
    public function userId()
    {
        return $this->userId;
    }
}
