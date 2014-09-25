<?php

namespace Lw\Infrastructure\Domain\Model\User;

use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserId;

/**
 * Class DoctrineUser
 * @package Lw\Domain\Model\User
 */
class DoctrineUser extends User
{
    /**
     * Surrogate Id
     * @var string
     */
    protected $surrogateUserId;

    /**
     * @param UserId $userId
     * @param string $email
     * @param string $password
     */
    public function __construct(UserId $userId, $email, $password)
    {
        parent::__construct($userId, $email, $password);
        $this->surrogateUserId = $userId->id();
    }

    /**
     * @return UserId
     */
    public function id()
    {
        return new UserId($this->surrogateUserId);
    }
}
