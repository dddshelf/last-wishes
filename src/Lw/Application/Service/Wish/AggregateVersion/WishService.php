<?php

namespace Lw\Application\Service\Wish\AggregateVersion;

use Ddd\Application\Service\ApplicationService;
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
}
