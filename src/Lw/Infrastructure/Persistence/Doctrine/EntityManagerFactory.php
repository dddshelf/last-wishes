<?php

namespace Lw\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory
{
    /**
     * @return EntityManager
     */
    public function build($conn)
    {
        \Doctrine\DBAL\Types\Type::addType('UserId', 'Lw\Infrastructure\Domain\Model\User\DoctrineUserId');
        \Doctrine\DBAL\Types\Type::addType('WishId', 'Lw\Infrastructure\Domain\Model\Wish\DoctrineWishId');

        return EntityManager::create(
            $conn,
            Setup::createYAMLMetadataConfiguration([__DIR__ . '/Mapping'], true)
        );
    }
}
