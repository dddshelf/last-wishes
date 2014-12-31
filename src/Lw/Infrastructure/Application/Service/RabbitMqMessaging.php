<?php

namespace Lw\Infrastructure\Application\Service;

use PhpAmqpLib\Connection\AMQPConnection;

class RabbitMqMessaging
{
    protected $connection;
    protected $channel;
    protected $exchangeName;

    public function __construct($exchangeName)
    {
        $this->exchangeName = $exchangeName;
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
        return $this->exchangeName;
    }

    protected function inName()
    {
        return $this->exchangeName;
    }
}
