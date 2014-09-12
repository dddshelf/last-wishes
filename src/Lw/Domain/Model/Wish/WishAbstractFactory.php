<?php

namespace Lw\Domain\Model\Wish;

interface WishAbstractFactory
{
    /**
     * @param $email
     * @param $content
     * @return Wish
     */
    public function makeEmailWish($email, $content);
}
