<?php

namespace Lw\Infrastructure\Domain\Model\Wish;

use Lw\Domain\Model\Wish\WishEmail;
use Lw\Domain\Model\Wish\WishId;

class WishFactory implements WishAbrastactFactory
{
    public function makeEmailWish($title, $email, $content)
    {
        return new WishEmail(
            new WishId(),
            $title,
            $email,
            $content
        );
    }
}
