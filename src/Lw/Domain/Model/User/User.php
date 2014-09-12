<?php

namespace Lw\Domain\Model\User;

use Assert\Assertion;
use Lw\Domain\Model\Wish\Wish;

/**
 * Class User
 * @package Lw\Domain\Model\User
 */
class User
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var Wish[]
     */
    private $wishes;

    private $createdOn;
    private $updatedOn;

    /**
     * Surrogate Id
     * @var string
     */
    private $surrogateUserId;

    /**
     * @param UserId $userId
     * @param string $email
     * @param string $password
     */
    public function __construct(UserId $userId, $email, $password)
    {
        $this->userId = $userId;
        $this->surrogateUserId = $userId->id();

        $this->setEmail($email);
        $this->changePassword($password);
        $this->wishes = [];
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
    }

    /**
     * @return UserId
     */
    public function id()
    {
        return new UserId($this->surrogateUserId);
    }

    /**
     * @return Wish[]
     */
    public function wishes()
    {
        return $this->wishes;
    }

    /**
     * @return string
     */
    public function email()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function changePassword($password)
    {
        $password = trim($password);
        if (!$password) {
            throw new \InvalidArgumentException('Password cannot be empty');
        }

        $this->password = $password;
    }

    /**
     * Option A: passing the Wish object
     * @param Wish $wish
     * @return $this
     */
    public function addWish(Wish $wish)
    {
        $this->wishes[] = $wish;

        return $this;
    }

    /**
     * Option B: Factory for wishes Wish
     * @param string $email
     * @param string $content
     */
    public function makeWish($email, $content)
    {
        $this->wishes[] = new EmailWish(
            $this->userId,
            $email,
            $content
        );
    }

    public function grantWishes()
    {
        $wishesGranted = 0;
        foreach ($this->wishes as $wish) {
            $wish->grant();
            $wishesGranted++;
        }

        return $wishesGranted;
    }

    /**
     * @param $email
     */
    private function setEmail($email)
    {
        $email = trim($email);
        if (!$email) {
            throw new \InvalidArgumentException('Email cannot be empty');
        }

        Assertion::email($email);
        $this->email = strtolower($email);
    }
}
