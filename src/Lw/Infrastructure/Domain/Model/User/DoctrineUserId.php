<?php

namespace Lw\Infrastructure\Domain\Model\User;

use Lw\Infrastructure\Domain\Model\DoctrineEntityId;

class DoctrineUserId extends DoctrineEntityId
{
    public function getName()
    {
        return 'UserId';
    }

    protected function getNamespace()
    {
        return 'Lw\Domain\Model\User';
    }
}
