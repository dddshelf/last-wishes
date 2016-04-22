<?php
require_once __DIR__.'/vendor/autoload.php';

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(
    (new Lw\Infrastructure\Ui\Web\Silex\Application())->bootstrap()['em']
);
