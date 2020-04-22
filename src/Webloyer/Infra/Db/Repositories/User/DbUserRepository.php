<?php

declare(strict_types=1);

namespace Webloyer\Infra\Db\Repositories\User;

use Webloyer\Domain\Model\User;
use Webloyer\Infra\Db\Eloquents\User\User as UserOrm;

class DbUserRepository implements User\UserRepository
{
    /**
     * @return User\Users
     * @see User\UserRepository::findAll()
     */
    public function findAll(): User\Users
    {
        $userArray = UserOrm::orderBy('name')
            ->get()
            ->map(function (UserOrm $userOrm): User\User {
                return $userOrm->toEntity();
            })
            ->toArray();
        return new User\Users(...$userArray);
    }

    /**
     * @param int|null $page
     * @param int|null $perPage
     * @return User\Users
     * @see User\UserRepository::findAllByPage()
     */
    public function findAllByPage(?int $page, ?int $perPage): User\Users
    {
        $page = $page ?? 1;
        $perPage = $perPage ?? 10;

        $userArray = UserOrm::orderBy('name')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (UserOrm $userOrm): User\User {
                return $userOrm->toEntity();
            })
            ->toArray();
        return new User\Users(...$userArray);
    }

    /**
     * @param User\UserEmail $email
     * @return User\User|null
     * @see User\UserRepository::findByEmail()
     */
    public function findByEmail(User\UserEmail $email): ?User\User
    {
        $userOrm = UserOrm::ofEmail($email->value())->first();
        if (is_null($userOrm)) {
            return null;
        }
        return $userOrm->toEntity();
    }

    /**
     * @param User\User $user
     * @return void
     * @see User\UserRepository::remove()
     */
    public function remove(User\User $user): void
    {
        $userOrm = UserOrm::ofEmail($user->email())->first();
        if (is_null($userOrm)) {
            return;
        }
        $userOrm->delete();
    }

    /**
     * @param User\User $user
     * @return void
     * @see User\UserRepository::save()
     */
    public function save(User\User $user): void
    {
        $userOrm = UserOrm::firstOrNew(['email' => $user->email()]);
        $user->provide($userOrm);
        $userOrm->save();
    }
}
