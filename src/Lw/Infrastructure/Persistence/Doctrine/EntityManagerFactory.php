<?php

namespace Lw\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory
{
    /**
     * @return EntityManager
     */
    public function build()
    {
        \Doctrine\DBAL\Types\Type::addType('UserId', 'Lw\Infrastructure\Domain\Model\User\DoctrineUserId');
        \Doctrine\DBAL\Types\Type::addType('WishId', 'Lw\Infrastructure\Domain\Model\Wish\DoctrineWishId');

        return EntityManager::create(
            array(
                'driver' => 'pdo_sqlite',
                'path' => __DIR__.'/../../../../../db.sqlite',
            ),
            Setup::createYAMLMetadataConfiguration([__DIR__.'/config'], true)
        );
    }
}
