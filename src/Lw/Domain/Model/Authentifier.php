<?php

namespace Lw\Domain\Model;

use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserRepository;

abstract class Authentifier
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @param string $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function authenticate($email, $password)
    {
        if ($this->isAlreadyAuthenticated()) {
            return true;
        }

        $user = $this->repository->userOfEmail($email);
        if (!$user) {
            return false;
        }

        if ($user->password() !== $password) {
            return false;
        }

        $this->persistAuthentication($user);
        return true;
    }

    abstract public function logout();
    abstract protected function persistAuthentication(User $user);
    abstract protected function isAlreadyAuthenticated();
}
