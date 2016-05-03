<?php

namespace Lw\Application\Service\Wish\AggregateVersion;

use Lw\Domain\Model\User\UserDoesNotExistException;
use Lw\Domain\Model\User\UserId;

class AddWishService extends WishService
{
    public function execute($request = null)
    {
        $userId = $request->userId();
        $address = $request->email();
        $content = $request->content();

        $user = $this->userRepository->ofId(new UserId($userId));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $user->makeWishAggregateVersion(
            $address,
            $content
        );
    }
}
