<?php

namespace Lw\Domain\Model\User;

use Ddd\Domain\DomainEventPublisher;
use Ddd\Domain\DomainEventSubscriber;

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

    /**
     * @test
     */
    public function itShouldPublishUserRegisteredEvent()
    {
        $id = DomainEventPublisher::instance()->subscribe($subscriber = new SpySubscriber());
        new User($userId = new UserId(), 'valid@email.com', 'password');
        DomainEventPublisher::instance()->unsubscribe($id);

        $this->assertUserRegisteredEventPublished($subscriber, $userId);
    }

    private function assertUserRegisteredEventPublished($subscriber, $userId)
    {
        $this->assertInstanceOf('Lw\Domain\Model\User\UserRegistered', $subscriber->domainEvent);
        $this->assertEquals($userId, $subscriber->domainEvent->userId());
    }
}

class SpySubscriber implements DomainEventSubscriber
{
    public $domainEvent;

    public function handle($aDomainEvent)
    {
        $this->domainEvent = $aDomainEvent;
    }

    public function isSubscribedTo($aDomainEvent)
    {
        return true;
    }
}
