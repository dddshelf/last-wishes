<?php

namespace Lw\Application\Service\Wish;

use Lw\Domain\Model\User\UserDoesNotExistException;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserRepository;
use Lw\Domain\Model\Wish\WishRepository;

class AddWishService extends WishService
{
    /**
     * @param string $userId
     * @param string $email
     * @param string $content
     * @throws UserDoesNotExistException
     */
    public function execute($userId, $email, $content)
    {
        $user = $this->userRepository->userOfId(new UserId($userId));
        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $this->wishRepository->persist(
            $user->makeWish(
                $this->wishRepository->nextIdentity(),
                $email,
                $content
            )
        );
    }
}
