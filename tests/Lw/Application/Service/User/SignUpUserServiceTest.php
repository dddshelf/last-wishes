<?php

namespace Lw\Application\Service\User;

use Lw\Application\DataTransformer\User\UserDtoDataTransformer;
use Lw\Domain\Model\User\UserId;
use Lw\Infrastructure\Persistence\InMemory\User\InMemoryUserRepository;

class SignUpUserServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Lw\Domain\Model\User\UserRepository
     */
    private $userRepository;

    /**
     * @var SignUpUserService
     */
    private $signInUserService;

    public function setUp()
    {
        $this->userRepository = new InMemoryUserRepository();
        $this->signInUserService = new SignUpUserService(
            $this->userRepository,
            new UserDtoDataTransformer()
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
        $request = new SignUpUserRequest('carlos.buenosvinos@gmail.com', 'foo');

        return $this->signInUserService->execute($request);
    }

    /**
     * @test
     */
    public function afterUserSignUpItShouldBeInTheRepository()
    {
        $user = $this->executeSignIn();

        $this->assertNotNull(
            $this->userRepository->ofId(new UserId($user['id']))
        );
    }
}
