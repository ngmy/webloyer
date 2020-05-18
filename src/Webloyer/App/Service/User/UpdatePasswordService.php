<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User;

class UpdatePasswordService extends UserService
{
    /**
     * @param UpdatePasswordRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $email = new UserEmail($request->getEmail());
        $user = $this->getNonNullUser($email);
        $user->changePassword($request->getPassword());
        $this->userRepository->save($user);
    }
}
