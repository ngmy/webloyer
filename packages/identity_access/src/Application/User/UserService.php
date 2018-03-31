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

    /**
     * Create a new application service instance.
     *
     * @param \Ngmy\Webloyer\IdentityAccess\Model\User\UserRepositoryInterface $userRepository
     * @return void
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Get all users.
     *
     * @return array
     */
    public function getAllUsers()
    {
        return $this->userRepository->allUsers();
    }

    /**
     * Get users by page.
     *
     * @param int $page
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getUsersByPage($page = 1, $perPage = 10)
    {
        return $this->userRepository->usersOfPage($page, $perPage);
    }

    /**
     * Get a user by id.
     *
     * @param int $userId
     * @return \Ngmy\Webloyer\IdentityAccess\Model\User\User
     */
    public function getUserById($userId)
    {
        return $this->userRepository->userOfId(new UserId($userId));
    }

    /**
     * Create or Update a user.
     *
     * @param int|null $userId
     * @param string   $name
     * @param string   $email
     * @param string   $password
     * @param string   $apiToken
     * @param int[]    $roleIds
     * @param string   $concurrencyVersion
     * @return void
     */
    public function saveUser($userId, $name, $email, $password, $apiToken, array $roleIds, $concurrencyVersion)
    {
        DB::transaction(function () use ($userId, $name, $email, $password, $apiToken, $roleIds, $concurrencyVersion) {
            if (!is_null($userId)) {
                $existsUser = $this->getUserById($userId);

                if (!is_null($existsUser)) {
                    $existsUser->failWhenConcurrencyViolation($concurrencyVersion);
                }
            }

            $user = new User(
                new UserId($userId),
                $name,
                $email,
                $password,
                $apiToken,
                array_map(function ($roleId) {
                    return new RoleId($roleId);
                }, $roleIds),
                null,
                null
            );

            $this->userRepository->save($user);
        });
    }

    /**
     * Remove a user.
     *
     * @param int $userId
     * @return void
     */
    public function removeUser($userId)
    {
        $this->userRepository->remove($this->getUserById($userId));
    }
}
