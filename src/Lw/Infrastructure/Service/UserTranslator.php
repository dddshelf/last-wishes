<?php

namespace Lw\Infrastructure\Service;

use Lw\Infrastructure\Domain\Model\User\FirstWillMadeBadge;
use Symfony\Component\PropertyAccess\PropertyAccess;

class UserTranslator
{
    public function toBadgesFromRepresentation($representation)
    {
        $accessor = PropertyAccess::createPropertyAccessor();

        $points = $accessor->getValue($representation, 'points');

        $badges = [];

        if ($points > 3) {
            $badges = new FirstWillMadeBadge();
        }

        return $badges;
    }
}
