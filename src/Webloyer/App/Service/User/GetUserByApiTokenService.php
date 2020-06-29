<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\{
    UserApiToken,
    UserDoesNotExistException,
};

class GetUserByApiTokenService extends UserService
{
    /**
     * @param GetUserByApiTokenRequest $request
     * @return mixed
     * @throws UserDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $apiToken = new UserApiToken($request->getApiToken());
        $user = $this->getNonNullUserByApiToken($apiToken);
        return $this->userDataTransformer->write($user)->read();
    }
}
