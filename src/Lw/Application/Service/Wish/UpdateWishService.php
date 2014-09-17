<?php

namespace Lw\Application\Service\Wish;

use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\WishId;

class UpdateWishService extends WishService
{
    /**
     * @param $userId
     * @param $wishId
     * @param $email
     * @param $content
     * @throws UserDoesNotExistException
     * @throws WishDoesNotExistException
     */
    public function execute($userId, $wishId, $email, $content)
    {
        $user = $this->userRepository->userOfId(new UserId($userId));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $wish = $this->wishRepository->wishOfId(new WishId($wishId));
        if (!$wish) {
            throw new WishDoesNotExistException();
        }

        if (!$wish->userId()->equals(new UserId($userId))) {
            throw new \InvalidArgumentException('User is not authorized to update this wish');
        }

        $wish->changeContent($content);
        $wish->changeEmail($email);

        $this->wishRepository->persist($wish);
    }
}
