<?php

namespace Lw\Application\Service\User\AggregateVersion;

use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserRepository;

class ViewWishesService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param ViewWishesRequest $request
     *
     * @return Wish[]
     */
    public function execute($request = null)
    {
        return $this->wishRepository->ofUserId(new UserId($request->userId()));
    }
}
