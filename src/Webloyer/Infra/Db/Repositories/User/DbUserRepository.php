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
        $recipeArray = UserOrm::orderBy('name')
            ->get()
            ->map(function (UserOrm $recipeOrm): User\User {
                return $recipeOrm->toEntity();
            })
            ->toArray();
        return new User\Users(...$recipeArray);
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

        $recipeArray = UserOrm::orderBy('name')
            ->skip($perPage * ($page - 1))
            ->take($perPage)
            ->get()
            ->map(function (UserOrm $recipeOrm): User\User {
                return $recipeOrm->toEntity();
            })
            ->toArray();
        return new User\Users(...$recipeArray);
    }

    /**
     * @param User\UserEmail $email
     * @return User\User|null
     * @see User\UserRepository::findByEmail()
     */
    public function findByEmail(User\UserEmail $email): ?User\User
    {
        $recipeOrm = UserOrm::ofEmail($email->value())->first();
        if (is_null($recipeOrm)) {
            return null;
        }
        return $recipeOrm->toEntity();
    }

    /**
     * @param User\User $recipe
     * @return void
     * @see User\UserRepository::remove()
     */
    public function remove(User\User $recipe): void
    {
        $recipeOrm = UserOrm::ofEmail($recipe->email())->first();
        if (is_null($recipeOrm)) {
            return;
        }
        $recipeOrm->delete();
    }

    /**
     * @param User\User $recipe
     * @return void
     * @see User\UserRepository::save()
     */
    public function save(User\User $recipe): void
    {
        $recipeOrm = UserOrm::firstOrNew(['email' => $recipe->email()]);
        $recipe->provide($recipeOrm);
        $recipeOrm->save();
    }
}
