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

        $this
            ->getUser($userId)
            ->deleteWish(new WishId($wishId));
    }
}
