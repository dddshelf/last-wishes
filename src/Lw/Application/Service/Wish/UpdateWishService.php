<?php

namespace Lw\Application\Service\Wish;

use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\WishEmail;
use Lw\Domain\Model\Wish\WishId;
use Lw\Domain\Model\Wish\WishRepository;

class UpdateWishService
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
     * @param $wishId
     * @param string $email
     * @param string $content
     * @throws WishDoesNotExistException
     */
    public function execute($userId, $wishId, $email, $content)
    {
        $wish = $this->wishRepository->wishOfId(new WishId($wishId));
        if (!$wish) {
            throw new WishDoesNotExistException();
        }

        if (!$wish->userId()->equals(new UserId($userId))) {
            throw new \InvalidArgumentException('User is not authorized to delete this wish');
        }

        $wish->changeContent($content);
        $wish->changeEmail($email);

        $this->wishRepository->persist(
            new WishEmail(new WishId(), $userId, $email, $email, $content)
        );
    }
}
