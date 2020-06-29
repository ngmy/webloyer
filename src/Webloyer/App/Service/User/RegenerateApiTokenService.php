<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\{
    UserDoesNotExistException,
    UserId,
};

class RegenerateApiTokenService extends UserService
{
    /**
     * @param RegenerateApiTokenRequest $request
     * @return void
     * @throws UserDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $id = new UserId($request->getId());
        $user = $this->getNonNullUser($id);
        $user->changeApiToken($request->getApiToken());
        $this->userRepository->save($user);
    }
}
