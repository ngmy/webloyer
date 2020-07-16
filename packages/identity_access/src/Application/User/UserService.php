<?php

namespace Ngmy\Webloyer\IdentityAccess\Application\User;

use DB;
use Illuminate\Pagination\LengthAwarePaginator;
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
    public function getAllUsers(): array
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
    public function getUsersByPage(int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        return $this->userRepository->usersOfPage($page, $perPage);
    }

    /**
     * Get a user by id.
     *
     * @param int $userId
     * @return \Ngmy\Webloyer\IdentityAccess\Model\User\User|null
     */
    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->userOfId(new UserId($userId));
    }

    /**
     * Create or Update a user.
     *
     * @param int|null    $userId
     * @param string      $name
     * @param string      $email
     * @param string      $password
     * @param string      $apiToken
     * @param int[]       $roleIds
     * @param string|null $concurrencyVersion
     * @return void
     */
    public function saveUser(?int $userId, string $name, string $email, string $password, string $apiToken, array $roleIds, ?string $concurrencyVersion): void
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
    public function removeUser(int $userId): void
    {
        $this->userRepository->remove($this->getUserById($userId));
    }
}
