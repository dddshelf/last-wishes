<?php

namespace Lw\Application\Service\Wish;

class UpdateWishRequest
{
    private $userId;
    private $email;
    private $content;
    private $wishId;

    /**
     * @param string $userId
     * @param string $wishId
     * @param string $email
     * @param string $content
     */
    public function __construct($userId, $wishId, $email, $content)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->content = $content;
        $this->wishId = $wishId;
    }

    /**
     * @return string
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
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
    public function content()
    {
        return $this->content;
    }
}
