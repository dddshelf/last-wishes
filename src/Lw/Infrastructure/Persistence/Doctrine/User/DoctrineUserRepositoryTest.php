<?php

namespace Lw\Application\Service\User;

use Doctrine\ORM\EntityManager;
use Lw\Domain\Model\User\UserId;
use Lw\Infrastructure\Persistence\Doctrine\EntityManagerFactory;

class DoctrineUserRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function setUp()
    {
        $this->entityManager = (new EntityManagerFactory)->build();
    }

    /**
     * @test
     */
    public function emptyRepository()
    {
        $userRepository = $this->entityManager->getRepository('Lw\\Domain\\Model\\User\\User');
        $this->assertNull($userRepository->userOfId(new UserId(1)));
    }
}
