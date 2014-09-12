<?php

namespace Lw\Application\Service\Wish;

use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\WishDoesNotExistException;
use Lw\Domain\Model\Wish\WishId;
use Lw\Domain\Model\Wish\WishRepository;

class DeleteWishService
{
    /**
     * @var WishRepository
     */
    private $wishRepository;

    public function __construct(WishRepository $wishRepository)
    {
        $this->wishRepository = $wishRepository;
    }

    public function execute($userId, $wishId)
    {
        $wish = $this->wishRepository->wishOfId(new WishId($wishId));
        if (!$wish) {
            throw new WishDoesNotExistException();
        }

        if (!$wish->userId()->equals(new UserId($userId))) {
            throw new \InvalidArgumentException('User is not authorized to delete this wish');
        }

        $this->wishRepository->remove($wish);
    }
}
