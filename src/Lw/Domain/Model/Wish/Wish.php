<?php

namespace Lw\Domain\Model\Wish;

use Assert\Assertion;
use Ddd\Domain\DomainEventPublisher;
use Lw\Domain\Model\User\UserId;

/**
 * Class Wish
 * @package Lw\Domain\Model\Wish
 */
class Wish
{
    /**
     * @var WishId
     */
    protected $wishId;

    /**
     * @var UserId
     */
    protected $userId;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var \DateTime
     */
    protected $updatedOn;

    /**
     * @param WishId $wishId
     * @param UserId $userId
     * @param string $address
     * @param string $content
     */
    public function __construct(WishId $wishId, UserId $userId, $address, $content)
    {
        $this->wishId = $wishId;
        $this->userId = $userId;

        $this->setContent($content);
        $this->setAddress($address);

        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
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

    private function setAddress($address)
    {
        $address = trim($address);
        if (!$address) {
            throw new \InvalidArgumentException('Address cannot be empty');
        }

        Assertion::notEmpty($address);
        $this->address = $address;
    }

    /**
     * @return WishId
     */
    public function id()
    {
        return $this->wishId;
    }

    /**
     * @return UserId
     */
    public function userId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function address()
    {
        return $this->address;
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

    public function grant() {
        DomainEventPublisher::instance()->publish(
            new WishGranted(
                $this->wishId
            )
        );
    }
}
