<?php

namespace Lw\Infrastructure\Persistence\Redis\Domain\Model\User;

use Lw\Domain\Model\User\User;
use Lw\Domain\Model\User\UserId;

class UserRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserRepository
     */
    private $repository;

    protected function setUp()
    {
        $this->repository = new UserRepository();
    }

    public function testPersistAndFetchUser()
    {
        $userId = new UserId();
        $user = new User(
            $userId,
            'foo@foo.com',
            'foo'
        );

        try {
            $this->repository->persist($user);
            $fetchedUser = $this->repository->userOfId($userId);
            $this->assertTrue($user->id()->equals($fetchedUser->id()));
        } catch(\Predis\Connection\ConnectionException $e) {
            $this->markTestSkipped('Redis is not running');
        }
    }
}
