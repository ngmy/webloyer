<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\{
    UserEmail,
};

class RegenerateApiTokenService extends UserService
{
    /**
     * @param RegenerateApiTokenRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $email = new UserEmail($request->getEmail());
        $user = $this->getNonNullUser($email);
        $user->changeApiToken($request->getApiToken());
        $this->userRepository->save($user);
    }
}
