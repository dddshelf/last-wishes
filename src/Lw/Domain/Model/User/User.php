<?php

namespace Lw\Domain\Model\User;

use Assert\Assertion;
use Ddd\Domain\DomainEventPublisher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Lw\Domain\Model\Wish\Wish;
use Lw\Domain\Model\Wish\WishId;

class User
{
    const MAX_LENGTH_EMAIL = 255;
    const MAX_LENGTH_PASSWORD = 255;
    const MAX_WISHES = 3;

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
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * @var \DateTime
     */
    protected $updatedOn;

    /**
     * @var ArrayCollection
     */
    protected $wishes;

    /**
     * @param UserId $userId
     * @param string $email
     * @param string $password
     */
    public function __construct(UserId $userId, $email, $password)
    {
        $this->userId = $userId;
        $this->setEmail($email);
        $this->changePassword($password);
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
        $this->wishes = new ArrayCollection();

        DomainEventPublisher::instance()->publish(
            new UserRegistered(
                $this->userId
            )
        );
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
     * @return UserId
     */
    public function id()
    {
        return $this->userId;
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

    public function makeWishNoAggregateVersion(WishId $wishId, $address, $content)
    {
        return new Wish(
            $wishId,
            $this->id(),
            $address,
            $content
        );
    }

    public function makeWishAggregateVersion($address, $content)
    {
        if (count($this->wishes) >= self::MAX_WISHES) {
            throw new NoMoreWishesAllowedException();
        }

        $this->wishes[] = new Wish(
            new WishId(),
            $this->id(),
            $address,
            $content
        );
    }

    public function numberOfRemainingWishes()
    {
        return self::MAX_WISHES - count($this->wishes);
    }

    public function grantWishes()
    {
        $wishesGranted = 0;
        foreach ($this->wishes as $wish) {
            $wish->grant();
            ++$wishesGranted;
        }

        return $wishesGranted;
    }

    public function updateWish(WishId $wishId, $address, $content)
    {
        foreach ($this->wishes as $k => $wish) {
            if ($wish->id()->equals($wishId)) {
                $wish->changeContent($content);
                $wish->changeAddress($address);
                break;
            }
        }
    }

    public function deleteWish(WishId $wishId)
    {
        // $wishes = $this->wishes->matching(Criteria::create()->where(Criteria::expr()->eq('id', $wishId)));
        // foreach ($wishes as $wish) {
        //    $this->wishes->removeElement($wish);
        // }

        foreach ($this->wishes as $k => $wish) {
            if ($wish->id()->equals($wishId)) {
                unset($this->wishes[$k]);
                break;
            }
        }
    }
}
