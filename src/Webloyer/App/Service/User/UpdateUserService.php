<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\UserId;

class UpdateUserService extends UserService
{
    /**
     * @param UpdateUserRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $id = new UserId($request->getId());
        $user = $this->getNonNullUser($id)
            ->changeEmail($request->getEmail())
            ->changeName($request->getName());
        $this->userRepository->save($user);
    }
}
