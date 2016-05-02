<?php

namespace Lw\Infrastructure\Domain\Model\User;

use Lw\Domain\Model\User\FirstWillMadeBadge as BaseBadge;

class FirstWillMadeBadge extends BaseBadge
{
    private $url = '/assets/first_will_made.png';

    public function getUrl()
    {
        return $this->url;
    }
}
