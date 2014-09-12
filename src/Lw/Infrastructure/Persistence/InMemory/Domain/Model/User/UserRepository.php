<?php

namespace Lw\Infrastructure\Persistence\InMemory\Domain\Model\User;

use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserId;

class UserRepository implements \Lw\Domain\Model\User\UserRepository
{
    /**
     * @var User[]
     */
    private $users;

    public function __construct()
    {
        $this->users = [];
    }

    /**
     * @param UserId $userId
     * @return User
     */
    public function userOfId(UserId $userId)
    {
        return isset($this->users[$this->getKey($userId)])
            ? $this->users[$this->getKey($userId)]
            : null;
    }

    /**
     * @param $email
     * @return User
     */
    public function userOfEmail($email)
    {
        foreach ($this->users as $user) {
            if ($user->email() === $email) {
                return $user;
            }
        }

        return null;
    }

    /**
     * @param User $user
     */
    public function persist(User $user)
    {
        $this->users[$this->getKey($user->id())] = $user;
    }

    /**
     * @param UserId $userId
     * @return mixed
     */
    private function getKey(UserId $userId)
    {
        return $userId->id();
    }
}
