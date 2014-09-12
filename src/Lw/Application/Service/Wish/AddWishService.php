<?php

namespace Lw\Application\Service\Wish;

use Lw\Domain\Model\User\UserDoesNotExistException;
use Lw\Domain\Model\Wish\WishEmail;
use Lw\Domain\Model\Wish\WishId;
use Lw\Domain\Model\Wish\WishRepository;

class AddWishService
{
    /**
     * @var WishRepository
     */
    private $wishRepository;

    public function __construct(WishRepository $wishRepository)
    {
        $this->wishRepository = $wishRepository;
    }

    /**
     * @param string $userId
     * @param string $email
     * @param string $content
     * @return mixed
     * @throws UserDoesNotExistException
     */
    public function execute($userId, $email, $content)
    {
        $wish = new WishEmail(new WishId(), $userId, $email, $email, $content);

        // $wish = $this->wishFactory->makeEmailWish($email, $content);
        return $this->wishRepository->persist($wish);

        /*
        $wish->addWish(
            $this->wishFactory->makeEmailWish($email, $content)
        );
        */

        $this->wishRepository->persist($wish);
    }
}
