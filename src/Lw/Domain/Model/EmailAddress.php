<?php

namespace Lw\Domain\Model;

class EmailAddress
{
    /**
     * @var string
     */
    private $email;

    /**
     * @param string $email
     */
    public function __construct($email)
    {
        $this->email = $email;
    }

    public function __toString()
    {
        return $this->email;
    }
}
