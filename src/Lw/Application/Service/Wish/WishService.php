<?php

namespace Lw\Application\Service\Wish;

use Ddd\Application\Service\ApplicationService;
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
}
