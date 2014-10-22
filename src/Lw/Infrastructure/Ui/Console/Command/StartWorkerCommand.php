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
            ->setName('demo:greet')
            ->setDescription('Greet someone')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getContainer();

        $notificationService = new NotificationService($container['em']);
        $notificationService->publishNotifications();

        /*
        $connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $channel = $connection->channel();
        $channel->queue_declare('lastwill.output', false, false, false, false);

        foreach($eventRepository->findAll() as $event) {
            $msg = new AMQPMessage($event->eventBody());
            $channel->basic_publish($msg, '', 'lastwill.output');
            $output->writeln('Event published into RabbitMQ');
        }

        $channel->close();
        $connection->close();
        */
    }
}
