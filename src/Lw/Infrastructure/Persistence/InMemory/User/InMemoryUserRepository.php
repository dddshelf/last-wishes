<?php

namespace Lw\Infrastructure\Persistence\InMemory\User;

use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private $users = array();

    /**
     * {@inheritdoc}
     */
    public function ofId(UserId $userId)
    {
        if (!isset($this->users[$userId->id()])) {
            return;
        }

        return $this->users[$userId->id()];
    }

    /**
     * {@inheritdoc}
     */
    public function ofEmail($email)
    {
        foreach ($this->users as $user) {
            if ($user->email() === $email) {
                return $user;
            }
        }

        return;
    }

    /**
     * {@inheritdoc}
     */
    public function add(User $user)
    {
        $this->users[$user->id()->id()] = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function nextIdentity()
    {
        return new UserId();
    }
}
