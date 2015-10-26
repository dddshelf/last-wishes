<?php

namespace Lw\Domain\Model;

use Ddd\Domain\DomainEventPublisher;
use Lw\Domain\Model\User\LogInAttempted;
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
        DomainEventPublisher::instance()->publish(new LogInAttempted($email));

        if ($this->isAlreadyAuthenticated()) {
            return true;
        }

        $user = $this->repository->ofEmail($email);
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
