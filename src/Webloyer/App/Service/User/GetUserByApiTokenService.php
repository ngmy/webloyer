<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\{
    User,
    UserApiToken,
};

class GetUserService extends UserService
{
    /**
     * @param GetUserRequest $request
     * @return User|null
     */
    public function execute($request = null)
    {
        $apiToken = new UserApiToken($request->getApiToken());
        return $this->userRepository->findByApiToken($apiToken);
    }
}
