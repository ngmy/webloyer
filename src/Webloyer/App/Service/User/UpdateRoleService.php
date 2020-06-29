<?php

declare(strict_types=1);

namespace Webloyer\App\Service\User;

use Webloyer\Domain\Model\User\{
    UserDoesNotExistException,
    UserId,
    UserRoleSpecification,
};

class UpdateRoleService extends UserService
{
    /**
     * @param UpdateRoleRequest $request
     * @return void
     * @throws UserDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $id = new UserId($request->getId());
        $user = $this->getNonNullUser($id);
        $user->removeAllRoles();
        foreach ($request->getRoles() as $role) {
            $user->addRole(UserRoleSpecification::$role());
        }
        $this->userRepository->save($user);
    }
}
