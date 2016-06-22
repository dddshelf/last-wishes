<?php

namespace Lw\Domain\Event;

use Ddd\Domain\DomainEventSubscriber;
use Elastica\Client;
use JMS\Serializer\SerializerBuilder;
use Monolog\Handler\ElasticSearchHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\WebProcessor;

class LoggerDomainEventSubscriber implements DomainEventSubscriber
{
    private $logger;
    private $serializer;

    public function __construct()
    {
        $this->logger = new Logger('main');
        $this->logger->pushHandler(new StreamHandler('/tmp/app.log'));

        $options = array(
            'index' => 'last_wishes_logs',
            'type' => 'log_entry',
        );

        $this->logger->pushHandler(new ElasticSearchHandler(new Client(), $options));
        $this->logger->pushProcessor(new WebProcessor());
        $this->logger->pushProcessor(new MemoryUsageProcessor());
        $this->logger->pushProcessor(new MemoryPeakUsageProcessor());

        $this->serializer = SerializerBuilder::create()->build();
    }

    public function handle($aDomainEvent)
    {
        $domainEventInArray = json_decode($this->serializer->serialize($aDomainEvent, 'json'), true);

        try {
            $this->logger->addInfo(
                get_class($aDomainEvent),
                $domainEventInArray + [
                    'name' => get_class($aDomainEvent),
                    'occurred_on' => $aDomainEvent->occurredOn(),
                ]
            );
        } catch (\Exception $e) {
        }
    }

    public function isSubscribedTo($aDomainEvent)
    {
        return true;
    }
}
