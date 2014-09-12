<?php

namespace Cyoa\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\EntityManager;
use Cyoa\Application\UseCase\TransactionalSession;

class Session implements TransactionalSession
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function executeAtomically(callable $operation)
    {
        $result = $this->entityManager->transactional($operation);
        $this->entityManager->flush();

        return $result;
    }
}
