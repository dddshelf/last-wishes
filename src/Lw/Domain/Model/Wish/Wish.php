<?php

namespace Lw\Domain\Model\Wish;

use Assert\Assertion;
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
     * @var string
     */
    protected $content;

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
     * @param string $content
     */
    public function __construct(WishId $wishId, UserId $userId, $title, $content)
    {
        $this->wishId = $wishId;
        $this->surrogateWishId = $wishId->id();

        $this->userId = $userId;
        $this->surrogateUserId = $userId->id();

        $this->setTitle($title);
        $this->setContent($content);

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

    public function changeTitle($title)
    {

    }

    public function changeContent($content)
    {
        $this->setContent($content);

        return $this;
    }

    public function content()
    {
        return $this->content;
    }

    /**
     * @param $content
     */
    protected function setContent($content)
    {
        $content = trim($content);
        if (!$content) {
            throw new \InvalidArgumentException('Message cannot be empty');
        }

        Assertion::notEmpty($content);
        $this->content = $content;
    }

    /**
     * @param $title
     */
    protected function setTitle($title)
    {
        $title = trim($title);
        if (!$title) {
            throw new \InvalidArgumentException('Title cannot be empty');
        }

        Assertion::notEmpty($title);
        $this->title = $title;
    }

    abstract public function grant();
}
