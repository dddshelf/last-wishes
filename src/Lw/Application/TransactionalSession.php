<?php

namespace Lw\Application;

/**
 * Interface TransactionalSession
 * @package Lw\Application
 */
interface TransactionalSession
{
    /**
     * @param callable $operation
     * @return mixed
     */
    public function executeAtomically(callable $operation);
}
