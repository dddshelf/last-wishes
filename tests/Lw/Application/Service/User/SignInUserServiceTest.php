<?php

namespace Lw\Application\Service\User;

use Lw\Infrastructure\Domain\Model\User\UserFactory;
use Lw\Infrastructure\Persistence\InMemory\User\InMemoryUserRepository;

class SignInUserServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Lw\Domain\Model\User\UserRepository
     */
    private $userRepository;

    /**
     * @var SignInUserService
     */
    private $signInUserService;

    public function setUp()
    {
        $this->userRepository = new InMemoryUserRepository();
        $this->signInUserService = new SignInUserService(
            $this->userRepository,
            new UserFactory()
        );
    }

    /**
     * @test
     * @expectedException \Lw\Domain\Model\User\UserAlreadyExistsException
     */
    public function alreadyExistingEmailShouldThrowAnException()
    {
        $this->executeSignIn();
        $this->executeSignIn();
    }

    private function executeSignIn()
    {
        $request = new SignInUserRequest('carlos.buenosvinos@gmail.com', 'foo');

        return $this->signInUserService->execute($request);
    }

    /**
     * @test
     */
    public function afterUserSignUpItShouldBeInTheRepository()
    {
        $user = $this->executeSignIn();

        $this->assertSame(
            $user,
            $this->userRepository->userOfId($user->id())
        );
    }
}
