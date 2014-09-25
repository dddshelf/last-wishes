<?php

namespace Lw\Infrastructure\Domain\Model\User;

use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\WishId;
use Lw\Infrastructure\Domain\Model\Wish\DoctrineWishEmail;

/**
 * Class DoctrineUser
 * @package Lw\Domain\Model\User
 */
class DoctrineUser extends User
{
    /**
     * Surrogate Id
     * @var string
     */
    protected $surrogateUserId;

    /**
     * @param UserId $userId
     * @param string $email
     * @param string $password
     */
    public function __construct(UserId $userId, $email, $password)
    {
        parent::__construct($userId, $email, $password);
        $this->surrogateUserId = $userId->id();
    }

    /**
     * @return UserId
     */
    public function id()
    {
        return new UserId($this->surrogateUserId);
    }

    /**
     * @param WishId $wishId
     * @param string $email
     * @param string $content
     * @return \Lw\Domain\Model\User\WishEmail
     */
    public function makeWish(WishId $wishId, $email, $content)
    {
        return new DoctrineWishEmail(
            $wishId,
            $this->id(),
            $email,
            $content
        );
    }
}
