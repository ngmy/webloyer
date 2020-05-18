<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\User;

class CreateUserService extends UserService
{
    /**
     * @param CreateUserRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $user = User::of(
            $request->getEmail(),
            $request->getName(),
            $request->getPassword(),
            $request->getApiToken()
        );
        $this->userRepository->save($user);
    }
}
