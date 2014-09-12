<?php

namespace Lw\Domain\Model\Wish;

use Lw\Domain\Model\User\UserId;

/**
 * Class Wish
 * @package Lw\Domain\Model\Wish
 */
abstract class Wish
{
    /**
     * @var WishId
     */
    protected $wishId;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var \DateTime
     */
    protected $updatedOn;

    /**
     * Surrogate Id
     * @var string
     */
    protected $surrogateWishId;

    /**
     * @var UserId
     */
    protected $userId;
    protected $surrogateUserId;

    /**
     * @param WishId $wishId
     * @param UserId $userId
     * @param string $title
     */
    public function __construct(WishId $wishId, UserId $userId, $title)
    {
        $this->wishId = $wishId;
        $this->surrogateWishId = $wishId->id();

        $this->userId = $userId;
        $this->surrogateUserId = $userId->id();

        $this->title = $title;
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
    }

    /**
     * @return WishId
     */
    public function id()
    {
        return new WishId($this->surrogateWishId);
    }

    /**
     * @return userId
     */
    public function userId()
    {
        return new UserId($this->surrogateUserId);
    }

    /**
     * @return string
     */
    public function title()
    {
        return $this->title;
    }

    abstract public function grant();
}
