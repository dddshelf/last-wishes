<?php

namespace Lw\Infrastructure\Domain\Model\Wish;

use Doctrine\DBAL\Types\GuidType;

class DoctrineWishId extends GuidType
{
    public function getName()
    {
        return 'WishId';
    }
}
