<?php

namespace Lw\Infrastructure\Ui\Console;

class Application extends \Symfony\Component\Console\Application
{
    private $container;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function getContainer()
    {
        return $this->container;
    }
}
