<?php

namespace Lw\Application\Service\Wish\AggregateVersion;

use Lw\Domain\Model\Wish\WishId;

class UpdateWishService extends WishService
{
    public function execute($request = null)
    {
        $userId = $request->userId();
        $wishId = $request->wishId();

        $this
            ->getUser($userId)
            ->updateWish(
                new WishId($wishId),
                $request->email(),
                $request->content()
            );
    }
}
