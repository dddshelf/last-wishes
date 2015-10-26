<?php

namespace Lw\Domain\Model\Wish;

use Lw\Domain\Model\User\UserId;

interface WishFactory
{
    /**
     * @param WishId $wishId
     * @param UserId $userId
     * @param $email
     * @param $content
     *
     * @return Wish
     */
    public function makeEmailWish(WishId $wishId, UserId $userId, $email, $content);
}
