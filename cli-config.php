<?php
require_once __DIR__.'/vendor/autoload.php';

use Lw\Infrastructure\Persistence\Doctrine\EntityManagerFactory;

$conn = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/db.sqlite',
);

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(
    (new EntityManagerFactory())->build($conn)
);
