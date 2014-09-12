<?php

namespace Lw\Domain\Model\Wish;

use Rhumsaa\Uuid\Uuid;

/**
 * Class WishId
 * @package Lw\Domain\Model\Wish
 */
class WishId
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
     * @param WishId $userId
     * @return boolean
     */
    public function equals(WishId $userId)
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
