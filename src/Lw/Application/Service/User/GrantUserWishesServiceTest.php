<?php

namespace Lw\Application\Service\User;

use Lw\Domain\Model\Wish\WishEmail;
use Lw\Domain\Model\Wish\WishId;
use Lw\Infrastructure\Persistence\InMemory\Domain\Model\User\UserRepository;

class GrantUserWishesServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function alreadyExistingEmailShouldThrowAnException()
    {
        $userRepository = new UserRepository();
        $service = new SignUpUserService($userRepository);
        $user = $service->execute('carlos.buenosvinos@gmail.com', 'foo');

        $service = new GrantUserWishesService($userRepository);
        $this->assertSame(0, $service->execute($user->id()->id()));
    }

    /**
     * @test
     */
    public function already2ExistingEmailShouldThrowAnException()
    {
        $userRepository = new UserRepository();
        $service = new SignUpUserService($userRepository);
        $user = $service->execute('carlos.buenosvinos@gmail.com', 'foo');

        $user->addWish(
            new WishEmail(
                new WishId(),
                'Help me Obi Wan Kanobi, you are my only hope',
                'carlos.buenosvinos@gmail.com',
                'General Kenobi. Years ago you served my father in the Clone Wars. Now he begs you to help him in his struggle against the Empire. I regret that I am unable to convey my father\'s request to you in person, but my ship has fallen under attack, and I\'m afraid my mission to bring you to Alderaan has failed. I have placed information vital to the survival of the Rebellion into the memory systems of this R2 unit. My father will know how to retrieve it. You must see this droid safely delivered to him on Alderaan. This is our most desperate hour. Help me, Obi-Wan Kenobi. You\'re my only hope.'
            )
        );

        $service = new GrantUserWishesService($userRepository);
        $this->assertSame(1, $service->execute($user->id()->id()));
    }

    /**
     * @test
     * @expectedException \Lw\Domain\Model\User\UserDoesNotExistException
     */
    public function grantingWishesForNonExistingUserShouldThrowAnException()
    {
        $userRepository = new UserRepository();
        $service = new GrantUserWishesService($userRepository);
        $this->assertNotNull(0, $service->execute(1));
    }
}
