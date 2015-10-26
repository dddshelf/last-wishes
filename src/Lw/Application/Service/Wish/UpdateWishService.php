<?php

namespace Lw\Application\Service\Wish;

use Lw\Domain\Model\User\UserDoesNotExistException;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\WishDoesNotExistException;
use Lw\Domain\Model\Wish\WishId;

class UpdateWishService extends WishService
{
    /**
     * @param $request
     *
     * @throws UserDoesNotExistException
     * @throws WishDoesNotExistException
     */
    public function execute($request = null)
    {
        $userId = $request->userId();
        $wishId = $request->wishId();
        $email = $request->email();
        $content = $request->content();
        $user = $this->userRepository->ofId(new UserId($userId));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $wish = $this->wishRepository->ofId(new WishId($wishId));
        if (!$wish) {
            throw new WishDoesNotExistException();
        }

        if (!$wish->userId()->equals(new UserId($userId))) {
            throw new \InvalidArgumentException('User is not authorized to update this wish');
        }

        $wish->changeContent($content);
        $wish->changeAddress($email);
    }
}
