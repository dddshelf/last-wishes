<?php

namespace Lw\Infrastructure\Ui\Console\Command;

use Lw\Application\Service\NotificationService;
use Lw\Application\Service\RabbitMqNotificationService;
use Lw\Infrastructure\Application\Service\RabbitMqMessageProducer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PushNotificationsCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('domain:events:spread')
            ->setDescription('Notify all domain events via messaging')
            ->addArgument('exchange-name', InputArgument::OPTIONAL, 'Exchange name to publish events to', NotificationService::EXCHANGE_NAME)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getApplication()->getContainer()['em'];

        $notificationService = new NotificationService(
            $em->getRepository('Lw\\Domain\\Model\\Event\\StoredEvent'),
            $em->getRepository('Lw\\Infrastructure\\Application\\PublishedMessage'),
            new RabbitMqMessageProducer()
        );

        $numberOfNotifications = $notificationService->publishNotifications($input->getArgument('exchange-name'));
        $output->writeln(sprintf('<comment>%d</comment> <info>notification(s) sent!</info>', $numberOfNotifications));
    }
}
