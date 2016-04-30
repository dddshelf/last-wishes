<?php

namespace Lw\Domain\Model\Wish;

use Lw\Domain\Model\User\UserId;

interface WishRepository
{
    /**
     * @param WishId $wishId
     *
     * @return Wish
     */
    public function ofId(WishId $wishId);

    /**
     * @param UserId $userId
     *
     * @return Wish[]
     */
    public function ofUserId(UserId $userId);

    /**
     * @param Wish $wish
     */
    public function add(Wish $wish);

    /**
     * @param Wish $wish
     */
    public function remove(Wish $wish);

    /**
     * @return WishId
     */
    public function nextIdentity();
}
