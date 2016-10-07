<?php

namespace Lw\Application\Service\Wish;

use Lw\Domain\Model\User\UserDoesNotExistException;

class AddWishService extends WishService
{
    /**
     * @param AddWishRequest $request
     *
     * @return void
     *
     * @throws UserDoesNotExistException
     */
    public function execute($request = null)
    {
        $userId = $request->userId();
        $address = $request->email();
        $content = $request->content();

        $user = $this->findUserOrFail($userId);

        $wish = $user->makeWishNoAggregateVersion(
            $this->wishRepository->nextIdentity(),
            $address,
            $content
        );

        $this->wishRepository->add($wish);
    }
}
