<?php

namespace Lw\Domain\Model\Wish;

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

    protected $user;

    /**
     * Surrogate Id
     * @var string
     */
    protected $id;

    /**
     * @param WishId $wishId
     * @param string $title
     */
    public function __construct(WishId $wishId, $title)
    {
        $this->wishId = $wishId;
        $this->id = $wishId->id();
        $this->title = $title;
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
    }

    /**
     * @return WishId
     */
    public function id()
    {
        return $this->wishId;
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
