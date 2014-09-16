<?php

namespace Lw\Domain\Model\User;

/**
 * Interface UserRepository
 * @package Lw\Domain\Model\User
 */
interface UserRepository
{
    /**
     * @param UserId $userId
     * @return User
     */
    public function userOfId(UserId $userId);

    /**
     * @param $email
     * @return User
     */
    public function userOfEmail($email);

    /**
     * @param User $user
     */
    public function persist(User $user);

    /**
     * @return UserId
     */
    public function nextIdentity();
}
