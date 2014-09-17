<?php

namespace Lw\Application\Service\Wish;

use Lw\Domain\Model\User\UserRepository;
use Lw\Domain\Model\Wish\WishRepository;

abstract class WishService
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
