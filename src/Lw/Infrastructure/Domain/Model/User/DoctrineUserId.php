<?php

namespace Lw\Infrastructure\Domain\Model\User;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Lw\Domain\Model\User\UserId;

class DoctrineUserId extends GuidType
{
    public function getName()
    {
        return 'UserId';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->id();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new UserId($value);
    }
}
