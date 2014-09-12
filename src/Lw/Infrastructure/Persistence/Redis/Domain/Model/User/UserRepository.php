<?php

namespace Lw\Infrastructure\Persistence\Redis\Domain\Model\User;

use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserId;
use Predis\Client;

class UserRepository implements \Lw\Domain\Model\User\UserRepository
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param UserId $userId
     * @return User
     */
    public function userOfId(UserId $userId)
    {
        $user = $this->client->get('user:'.$userId);
        if (null !== $user) {
            return unserialize($user);
        }

        return null;
    }

    /**
     * @param $email
     * @return User
     */
    public function userOfEmail($email)
    {
        $user = $this->client->get('user:'.$email);
        if (null !== $user) {
            return unserialize($user);
        }

        return null;
    }

    /**
     * @param User $user
     */
    public function persist(User $user)
    {
        $this->client->set(
            $this->generateKey($user),
            serialize($user)
        );
    }

    /**
     * @param User $user
     * @return string
     */
    private function generateKey(User $user)
    {
        return 'user:'.$user->email();
    }
}
