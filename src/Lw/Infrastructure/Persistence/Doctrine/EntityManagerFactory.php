<?php

namespace Lw\Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class EntityManagerFactory
{
    /**
     * @return EntityManager
     */
    public function build()
    {
        Type::addType('money', 'Lw\\Infrastructure\\Persistence\\Doctrine\\Type\\MoneyType');
        return EntityManager::create(
            array(
                'driver'   => 'pdo_mysql',
                'user'     => 'root',
                'password' => '',
                'dbname'   => 'ddd',
            ),
            Setup::createYAMLMetadataConfiguration([__DIR__.'/config'], true)
        );
    }
}
