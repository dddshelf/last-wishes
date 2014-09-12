<?php

namespace Lw\Application\Service\User;

use Lw\Domain\Model\Authentifier;

class LogOutUserService
{
    /**
     * @var Authentifier
     */
    private $authenticationService;

    public function __construct(Authentifier $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function execute()
    {
        return $this->authenticationService->logout();
    }
}
