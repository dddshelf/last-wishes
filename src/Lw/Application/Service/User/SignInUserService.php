<?php

namespace Lw\Application\Service\User;

use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserAlreadyExistsException;
use Lw\Domain\Model\User\UserRepository;

/**
 * Class SignInUserService
 * @package Lw\Application\Service\User
 */
class SignInUserService implements Service
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param SignInUserRequest $request
     * @return User
     * @throws UserAlreadyExistsException
     */
    public function execute(SignInUserRequest $request)
    {
        $email = $request->email();
        $password = $request->password();

        $user = $this->userRepository->userOfEmail($email);
        if (null !== $user) {
            throw new UserAlreadyExistsException();
        }

        $user = new User(
            $this->userRepository->nextIdentity(),
            $email,
            $password
        );

        $this->userRepository->persist($user);

        return $user;
    }
}
