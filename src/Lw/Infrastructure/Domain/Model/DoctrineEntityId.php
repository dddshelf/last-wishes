<?php

namespace Lw\Infrastructure\Domain\Model;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class DoctrineEntityId extends GuidType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->id();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $className = $this->getNamespace().'\\'.$this->getName();

        return new $className($value);
    }
}
