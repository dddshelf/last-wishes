<?php

namespace Lw\Domain\Model\User;

/**
 * Class User.
 */
class UserSecurityToken
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var string
     */
    private $email;

    /**
     * @param User $user
     *
     * @return UserSecurityToken
     */
    public static function fromUser(User $user)
    {
        return new self($user->id(), $user->email());
    }

    /**
     * @param UserId $userId
     * @param string $email
     */
    private function __construct(UserId $userId, $email)
    {
        $this->userId = $userId;
        $this->email = $email;
    }

    /**
     * @return UserId
     */
    public function id()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }
}
