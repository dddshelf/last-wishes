<?php

namespace Lw\Application\Service\Wish\AggregateVersion;

use Lw\Domain\Model\User\UserDoesNotExistException;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\WishId;

class UpdateWishService extends WishService
{
    public function execute($request = null)
    {
        $userId = $request->userId();
        $wishId = $request->wishId();

        $user = $this->userRepository->ofId(new UserId($userId));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $user->updateWish(
            new WishId($wishId),
            $request->email(),
            $request->content()
        );
    }
}
