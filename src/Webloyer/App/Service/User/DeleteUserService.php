<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\UserId;

class DeleteUserService extends UserService
{
    /**
     * @param DeleteUserRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $id = new UserId($request->getId());
        $user = $this->getNonNullUser($id);
        $this->userRepository->remove($user);
    }
}
