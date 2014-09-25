<?php

namespace Lw\Infrastructure\Domain\Model\Wish;

use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\WishFactory;
use Lw\Domain\Model\Wish\WishId;

class DoctrineWishFactory implements WishFactory
{
    /**
     * @param WishId $wishId
     * @param UserId $userId
     * @param $email
     * @param $content
     * @return DoctrineWishEmail
     */
    public function makeEmailWish(WishId $wishId, UserId $userId, $email, $content)
    {
        return new DoctrineWishEmail(
            $wishId,
            $userId,
            $email,
            $content
        );
    }
}
