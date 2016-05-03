<?php

namespace Lw\Application\Service\Wish\AggregateVersion;

use Ddd\Application\Service\ApplicationService;
use Lw\Domain\Model\User\UserDoesNotExistException;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserRepository;

abstract class WishService implements ApplicationService
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param string $userId
     * @return \Lw\Domain\Model\User\User
     * @throws UserDoesNotExistException
     */
    protected function getUser($userId)
    {
        $user = $this->userRepository->ofId(new UserId($userId));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        return $user;
    }
}
