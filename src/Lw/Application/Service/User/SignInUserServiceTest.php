<?php

namespace Lw\Application\Service\User;

use Lw\Infrastructure\Persistence\InMemory\Domain\Model\User\UserRepository;

class SignInUserServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \Lw\Domain\Model\User\UserAlreadyExistsException
     */
    public function alreadyExistingEmailShouldThrowAnException()
    {
        $service = new SignInUserService(new UserRepository());
        $service->execute('carlos.buenosvinos@gmail.com', 'foo');
        $service->execute('carlos.buenosvinos@gmail.com', 'foo');
    }

    /**
     * @test
     */
    public function afterUserSignUpItShouldBeInTheRepository()
    {
        $userRepository = new UserRepository();
        $service = new SignInUserService($userRepository);
        $user = $service->execute('carlos.buenosvinos@gmail.com', 'foo');

        $this->assertSame(
            $user,
            $userRepository->userOfId($user->id())
        );
    }
}
