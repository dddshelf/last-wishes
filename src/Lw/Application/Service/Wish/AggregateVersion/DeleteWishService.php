<?php

namespace Lw\Application\Service\Wish\AggregateVersion;

use Lw\Domain\Model\User\UserDoesNotExistException;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\WishId;

class DeleteWishService extends WishService
{
    public function execute($request = null)
    {
        $userId = $request->userId();
        $wishId = $request->wishId();

        $user = $this->userRepository->ofId(new UserId($userId));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $user->deleteWish(new WishId($wishId));
    }
}
