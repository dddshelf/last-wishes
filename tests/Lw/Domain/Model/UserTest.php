<?php

namespace Lw\Domain\Model\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function emptyEmailShouldThrowException()
    {
        $this->createUserWithEmail('');
    }

    private function createUserWithEmail($email)
    {
        return new User(new UserId(), $email, 'irrelevant-password');
    }

    /**
     * @test
     * @expectedException \Assert\AssertionFailedException
     */
    public function invalidEmailShouldThrowException()
    {
        $this->createUserWithEmail('invalid@email');
    }

    /**
     * @test
     * @dataProvider sanitizedEmails
     */
    public function itShouldSanitizeEmail($email, $expectedEmail)
    {
        $user = $this->createUserWithEmail($email);

        $this->assertEquals($expectedEmail, $user->email());
    }

    public function sanitizedEmails()
    {
        return [
            ['user@example.com', 'user@example.com'],
            ['USER@EXAMPLE.COM', 'user@example.com'],
            ['   user@example.com ', 'user@example.com'],
        ];
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function emptyPasswordShouldThrowException()
    {
        return new User(new UserId(), 'valid@email.com', '');
    }
}
