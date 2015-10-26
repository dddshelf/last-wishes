<?php

namespace Lw\Domain\Model\User;

/**
 * Interface UserRepository.
 */
interface UserRepository
{
    /**
     * @param UserId $userId
     *
     * @return User
     */
    public function ofId(UserId $userId);

    /**
     * @param string $email
     *
     * @return User
     */
    public function ofEmail($email);

    /**
     * @param User $user
     */
    public function add(User $user);

    /**
     * @return UserId
     */
    public function nextIdentity();
}
