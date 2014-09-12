<?php

namespace Lw\Application\Service\Wish;

use Lw\Domain\Model\User\UserDoesNotExistException;
use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\User\UserRepository;
use Lw\Domain\Model\Wish\WishAbstractFactory;

class AddWishService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository, WishAbstractFactory $wishFactory)
    {
        $this->userRepository = $userRepository;
        $this->wishFactory = $wishFactory;
    }

    /**
     * @param string $userId
     * @param string $email
     * @param string $content
     * @throws UserDoesNotExistException
     */
    public function execute($userId, $email, $content)
    {
        $user = $this->userRepository->userOfId(
            new UserId($userId)
        );

        if (null === $user) {
            throw new UserDoesNotExistException();
        }

        $user->addWish(
            $this->wishFactory->makeEmailWish($email, $content)
        );

        $this->userRepository->persist($user);
    }
}
