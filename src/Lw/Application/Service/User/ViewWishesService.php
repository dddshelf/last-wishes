<?php

namespace Lw\Application\Service\User;

use Lw\Domain\Model\User\UserId;
use Lw\Domain\Model\Wish\WishRepository;

class ViewWishesService
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
     * @param int $userId
     * @return Wish[]
     */
    public function execute($userId)
    {
        return $this->wishRepository->wishesOfUserId(new UserId($userId));
    }
}
