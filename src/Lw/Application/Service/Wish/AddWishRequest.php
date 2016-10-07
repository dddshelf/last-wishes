<?php

namespace Lw\Application\Service\Wish;

class AddWishRequest
{
    /**
     * @var string
     */
    private $userId;
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $content;

    /**
     * @param string $userId
     * @param string $email
     * @param string $content
     */
    public function __construct($userId, $email, $content)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->content = $content;
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
    public function content()
    {
        return $this->content;
    }
}
