<?php

namespace Lw\Infrastructure\Application\Service;

use PhpAmqpLib\Connection\AMQPConnection;

class RabbitMqMessaging
{
    const EXCHANGE_NAME = 'lastwill';

    protected $connection;
    protected $channel;

    public function __construct()
    {
        $this->connection = new AMQPConnection('localhost', 5672, 'guest', 'guest');
        $this->channel = $this->connection->channel();
        $this->channel->exchange_declare($this->outName(), 'fanout', false, true, false);
        $this->channel->queue_declare($this->inName(), false, true, false, false);
        $this->channel->queue_bind($this->inName(), $this->outName());
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }

    protected function outName()
    {
        return self::EXCHANGE_NAME.'.out';
    }

    protected function inName()
    {
        return self::EXCHANGE_NAME.'.in';
    }
}
