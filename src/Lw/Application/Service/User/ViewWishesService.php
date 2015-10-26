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
     * @param ViewWishesRequest $request
     *
     * @return Wish[]
     */
    public function execute($request = null)
    {
        return $this->wishRepository->ofUserId(new UserId($request->userId()));
    }
}
