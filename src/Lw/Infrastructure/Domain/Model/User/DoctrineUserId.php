<?php

namespace Lw\Infrastructure\Domain\Model\User;

use Doctrine\DBAL\Types\GuidType;

class DoctrineUserId extends GuidType
{
    public function getName()
    {
        return 'UserId';
    }
}
