<?php

namespace Lw\Domain\Model\User;

use Rhumsaa\Uuid\Uuid;

/**
 * Class UserId
 * @package Lw\Domain\Model\User
 */
class UserId
{
    /**
     * @var string
     */
    private $id;

    /**
     * @param string $id
     */
    public function __construct($id = null)
    {
        $this->id = null === $id ? Uuid::uuid4()->toString() : $id;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param UserId $userId
     * @return boolean
     */
    public function equals(UserId $userId)
    {
        return $this->id() === $userId->id();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id();
    }
}
