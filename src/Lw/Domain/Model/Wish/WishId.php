<?php

namespace Lw\Domain\Model\Wish;

use Ramsey\Uuid\Uuid;

/**
 * Class WishId.
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
        $this->id = $id ?: Uuid::uuid4()->toString();
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @param WishId $wishId
     *
     * @return bool
     */
    public function equals(WishId $wishId)
    {
        return $this->id() === $wishId->id();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id();
    }
}
