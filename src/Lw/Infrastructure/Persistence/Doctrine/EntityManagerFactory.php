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
        return EntityManager::create(
            array(
                'driver' => 'pdo_sqlite',
                'path' => __DIR__.'/../../../../../db.sqlite',
            ),
            Setup::createYAMLMetadataConfiguration([__DIR__.'/config'], true)
        );
    }
}
