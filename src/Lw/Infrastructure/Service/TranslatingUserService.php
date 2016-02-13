<?php

namespace Lw\Infrastructure\Service;

use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserService;

class TranslatingUserService implements UserService
{
    /**
     * @var UserAdapter
     */
    private $userAdapter;

    public function __construct(UserAdapter $userAdapter)
    {
        $this->userAdapter = $userAdapter;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function badgesFrom($id)
    {
        return $this->userAdapter->toBadges($id);
    }
}