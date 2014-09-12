<?php
require_once __DIR__.'/vendor/autoload.php';

use Lw\Infrastructure\Persistence\Doctrine\EntityManagerFactory;

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(
    (new EntityManagerFactory())->build()
);
