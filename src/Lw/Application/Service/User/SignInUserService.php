<?php

namespace Lw\Application\Service\User;

use Ddd\Application\Service\ApplicationService;
use Lw\Application\Service;
use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserAlreadyExistsException;
use Lw\Domain\Model\User\UserFactory;
use Lw\Domain\Model\User\UserRepository;

/**
 * Class SignInUserService
 * @package Lw\Application\Service\User
 */
class SignInUserService implements ApplicationService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @param UserRepository $userRepository
     * @param UserFactory $userFactory
     */
    public function __construct(UserRepository $userRepository, UserFactory $userFactory)
    {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
    }

    /**
     * @param SignInUserRequest $request
     * @return User
     * @throws UserAlreadyExistsException
     */
    public function execute($request = null)
    {
        $email = $request->email();
        $password = $request->password();

        $user = $this->userRepository->userOfEmail($email);
        if (null !== $user) {
            throw new UserAlreadyExistsException();
        }

        $user = $this->userFactory->build(
            $this->userRepository->nextIdentity(),
            $email,
            $password
        );

        $this->userRepository->persist($user);

        return $user;
    }
}
