<?php

namespace Lw\Domain\Model\Wish;

use Lw\Domain\Model\User\UserId;

/**
 * Interface WishRepository
 * @package Lw\Domain\Model\Wish
 */
interface WishRepository
{
    /**
     * @param WishId $wishId
     * @return Wish
     */
    public function wishOfId(WishId $wishId);

    /**
     * @param UserId $userId
     * @return Wish[]
     */
    public function wishesOfUserId(UserId $userId);

    /**
     * @param Wish $wish
     * @return mixed
     */
    public function persist(Wish $wish);

    /**
     * @param Wish $wish
     */
    public function remove(Wish $wish);
}
