<?php

namespace Lw\Domain\Model\Wish;

use DateTime;
use DateTimeZone;
use Ddd\Domain\DomainEvent;
use Ddd\Domain\Event\PublishableDomainEvent;
use Lw\Domain\Model\User\UserId;

class WishWasMade implements DomainEvent, PublishableDomainEvent
{
    /**
     * @var WishId
     */
    private $wishId;

    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTimeImmutable
     */
    private $occurredOn;

    public function __construct(WishId $wishId, UserId $userId, $address, $content)
    {
        $this->wishId = $wishId;
        $this->userId = $userId;
        $this->address = $address;
        $this->content = $content;
        $this->occurredOn = new \DateTimeImmutable('now', new DateTimeZone('UTC'));
    }

    /**
     * @return WishId
     */
    public function wishId()
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

    /**
     * @return string
     */
    public function content()
    {
        return $this->content;
    }

    /**
     * @return \DateTime
     */
    public function occurredOn()
    {
        return $this->occurredOn;
    }
}
