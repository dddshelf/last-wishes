<?php

namespace Lw\Infrastructure\Domain\Model\Wish;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use Lw\Domain\Model\Wish\WishId;

class DoctrineWishId extends GuidType
{
    public function getName()
    {
        return 'WishId';
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->id();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new WishId($value);
    }
}
