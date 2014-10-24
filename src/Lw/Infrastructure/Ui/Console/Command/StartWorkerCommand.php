<?php

namespace Lw\Infrastructure\Ui\Console\Command;

use Lw\Application\Service\NotificationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartWorkerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('notifications:push')
            ->setDescription('Notify all domain events via messaging')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (new NotificationService(
            $this->getApplication()->getContainer()['em']))
            ->publishNotifications();
    }
}
