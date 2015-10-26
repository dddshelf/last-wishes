<?php

namespace Lw\Infrastructure\Domain\Model\Wish;

use Lw\Infrastructure\Domain\Model\DoctrineEntityId;

class DoctrineWishId extends DoctrineEntityId
{
    public function getName()
    {
        return 'WishId';
    }

    protected function getNamespace()
    {
        return 'Lw\Domain\Model\Wish';
    }
}
