<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\UserEmail;

class DeleteUserService extends UserService
{
    /**
     * @param DeleteUserRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $email = new UserEmail($request->getEmail());
        $user = $this->getNonNullUser($email);
        $this->userRepository->remove($user);
    }
}
