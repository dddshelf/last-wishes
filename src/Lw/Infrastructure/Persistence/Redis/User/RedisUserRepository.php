<?php

namespace Lw\Infrastructure\Persistence\Redis\User;

use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserRepository;

class RedisUserRepository implements UserRepository
{
    private $predis;

    public function __construct($predis)
    {
        $this->predis = $predis;
    }

    /**
     * {@inheritdoc}
     */
    public function userOfId(UserId $userId)
    {
        $content = $this->predis->get($userId);
        if ($content && $user = unserialize($content)) {
            return $user;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function userOfEmail($email)
    {
        $content = $this->predis->get($email);
        if ($content && $user = unserialize($content)) {
            return $user;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function persist(User $user)
    {
        $serializedUser = serialize($user);
        $this->predis->set($user->id()->id(), $serializedUser);
        $this->predis->set($user->email(), serialize($user));
    }

    /**
     * {@inheritdoc}
     */
    public function nextIdentity()
    {
        return new UserId();
    }
}
