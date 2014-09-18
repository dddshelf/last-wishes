<?php

namespace Lw\Application;

class TransactionalService
{
    /**
     * @var TransactionalSession
     */
    private $session;
    private $service;

    public function __construct($service, TransactionalSession $session)
    {
        $this->session = $session;
        $this->service = $service;
    }

    public function execute($request)
    {
        if (empty($this->service)) {
            throw new \LogicException('A use case must be specified');
        }

        $operation = function () use ($request) {
            return $this->service->execute($request);
        };

        return $this->session->executeAtomically($operation->bindTo($this));
    }
}
