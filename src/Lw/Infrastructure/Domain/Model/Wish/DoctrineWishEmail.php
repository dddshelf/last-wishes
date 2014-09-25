<?php

namespace Lw\Infrastructure\Domain\Model\Wish;

use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\WishEmail;
use Lw\Domain\Model\Wish\WishId;

/**
 * Class Wish
 * @package Lw\Domain\Model\Wish
 */
class DoctrineWishEmail extends WishEmail
{
    /**
     * Surrogate Id
     * @var string
     */
    private $surrogateWishId;

    /**
     * User surrogate Id
     * @var string
     */
    private $surrogateUserId;

    /**
     * @{inheritDoc}
     */
    public function __construct(WishId $wishId, UserId $userId, $email, $content)
    {
        parent::__construct($wishId, $userId, $email, $content);
        $this->surrogateWishId = $wishId->id();
        $this->surrogateUserId = $userId->id();
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
}
