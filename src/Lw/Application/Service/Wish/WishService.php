<?php

namespace Lw\Application\Service\Wish;

use Ddd\Application\Service\ApplicationService;
use Lw\Domain\Model\User\UserDoesNotExistException;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserRepository;
use Lw\Domain\Model\Wish\WishRepository;

abstract class WishService implements ApplicationService
{
    /**
     * @var WishRepository
     */
    protected $wishRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(UserRepository $userRepository, WishRepository $wishRepository)
    {
        $this->userRepository = $userRepository;
        $this->wishRepository = $wishRepository;
    }

    protected function findUserOrFail($userId)
    {
        $user = $this->userRepository->ofId(new UserId($userId));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        return $user;
    }
}
