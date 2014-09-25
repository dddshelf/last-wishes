<?php

namespace Lw\Domain\Model\User;

use Assert\Assertion;
use Lw\Domain\Model\Wish\Wish;
use Lw\Domain\Model\Wish\WishEmail;
use Lw\Domain\Model\Wish\WishId;

/**
 * Class User
 * @package Lw\Domain\Model\User
 */
class User
{
    /**
     * @var UserId
     */
    protected $userId;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var Wish[]
     */
    protected $wishes;

    protected $createdOn;
    protected $updatedOn;

    /**
     * Surrogate Id
     * @var string
     */
    protected $surrogateUserId;

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
        return $this->userId;
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
            throw new \InvalidArgumentException('password');
        }

        $this->password = $password;
    }

    /**
     * @param WishId $wishId
     * @param string $email
     * @param string $content
     * @return \Lw\Domain\Model\User\WishEmail
     */
    public function makeWish(WishId $wishId, $email, $content)
    {
        return new WishEmail(
            $wishId,
            $this->id(),
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
    protected function setEmail($email)
    {
        $email = trim($email);
        if (!$email) {
            throw new \InvalidArgumentException('email');
        }

        Assertion::email($email);
        $this->email = strtolower($email);
    }
}
