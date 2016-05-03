<?php

namespace Lw\Application\Service\Wish\AggregateVersion;

class AddWishService extends WishService
{
    public function execute($request = null)
    {
        $userId = $request->userId();
        $address = $request->email();
        $content = $request->content();

        $this
            ->getUser($userId)
            ->makeWishAggregateVersion(
                $address,
                $content
            );
    }
}
