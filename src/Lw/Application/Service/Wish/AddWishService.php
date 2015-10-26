<?php

namespace Lw\Application\Service\Wish;

use Lw\Domain\Model\User\UserDoesNotExistException;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserRepository;
use Lw\Domain\Model\Wish\WishRepository;

class AddWishService extends WishService
{
    /**
     * @param AddWishRequest $request
     * @return mixed|void
     * @throws UserDoesNotExistException
     */
    public function execute($request = null)
    {
        $userId = $request->userId();
        $email = $request->email();
        $content = $request->content();

        $user = $this->userRepository->ofId(new UserId($userId));
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
