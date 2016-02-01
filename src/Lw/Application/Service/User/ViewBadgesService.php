<?php

namespace Lw\Application\Service\User;

use Lw\Domain\Model\User\UserService;

class ViewBadgesService
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function execute($request = null)
    {
        return $this->userService->badgesFrom($request->userId());
    }
}
