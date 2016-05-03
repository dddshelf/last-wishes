<?php

namespace Lw\Application\Service\Wish\AggregateVersion;

use Lw\Domain\Model\User\UserDoesNotExistException;
use Lw\Domain\Model\Wish\WishDoesNotExistException;

class ViewWishService extends WishService
{
    /**
     * @param ViewWishRequest $request
     *
     * @return \Lw\Domain\Model\Wish\Wish
     *
     * @throws UserDoesNotExistException
     * @throws WishDoesNotExistException
     */
    public function execute($request = null)
    {
        $userId = $request->userId();
        $wishId = $request->wishId();

        return $this
            ->getUser($userId)
            ->wishOfId($wishId);
    }
}
