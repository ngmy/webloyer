<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User;

class UpdateUserService extends UserService
{
    /**
     * @param UpdateUserRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $email = new UserEmail($request->getEmail());
        $user = $this->getNonNullUser($email)
            ->changeName($request->getName());
        $this->userRepository->save($user);
    }
}
