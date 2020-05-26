<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\UserId;

class RegenerateApiTokenService extends UserService
{
    /**
     * @param RegenerateApiTokenRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $id = new UserId($request->getId());
        $user = $this->getNonNullUser($id);
        $user->changeApiToken($request->getApiToken());
        $this->userRepository->save($user);
    }
}
