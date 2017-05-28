<?php

namespace Ngmy\Webloyer\IdentityAccess\Application\User;

use DB;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;

class UserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->allUsers();
    }

    public function getUserOfId($id)
    {
        $userId = new UserId($id);

        return $this->userRepository->userOfId($userId);
    }

    public function getUsersOfPage($page, $perPage)
    {
        return $this->userRepository->usersOfPage($page, $perPage);
    }

    public function saveUser($id, $name, $email, $password, $apiToken, array $primitiveRoleIds, $concurrencyVersion)
    {
        $user = DB::transaction(function () use ($id, $name, $email, $password, $apiToken, $primitiveRoleIds, $concurrencyVersion) {
            if (!is_null($id)) {
                $existsUser = $this->getUserOfId($id);

                if (!is_null($existsUser)) {
                    $existsUser->failWhenConcurrencyViolation($concurrencyVersion);
                }
            }

            $user = new User(
                new UserId($id),
                $name,
                $email,
                $password,
                $apiToken,
                array_map(function ($roleId) {
                    return new RoleId($roleId);
                }, $primitiveRoleIds),
                null,
                null
            );

            return $this->userRepository->save($user);
        });

        return $user;
    }

    public function removeUser($id)
    {
        $user = $this->getUserOfId($id);

        $this->userRepository->remomve($user);

        return true;
    }
}
