<?php

namespace Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\User as EloquentUser;

class EloquentUserRepository implements UserRepositoryInterface
{
    private $eloquentUser;

    /**
     * Create a new repository instance.
     *
     * @param \Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\User $eloquentUser
     * @return void
     */
    public function __construct(EloquentUser $eloquentUser)
    {
        $this->eloquentUser = $eloquentUser;
    }

    public function allUsers()
    {
        $eloquentUsers = $this->eloquentUser->all();

        $users = $eloquentUsers->map(function ($eloquentUser, $key) {
            return $this->toEntity($eloquentUser);
        })->all();

        return $users;
    }

    public function usersOfPage($page = 1, $limit = 10)
    {
        $eloquentUsers = $this->eloquentUser
            ->orderBy('name')
            ->get();

        $users = $eloquentUsers
            ->slice($limit * ($page - 1), $limit)
            ->map(function ($eloquentUser, $key) {
                return $this->toEntity($eloquentUser);
            });

        return new LengthAwarePaginator(
            $users,
            $eloquentUsers->count(),
            $limit,
            $page,
            [
                'path' => Paginator::resolveCurrentPath(),
            ]
        );
    }

    public function userOfId(UserId $userId)
    {
        $primaryKey = $userId->id();

        $eloquentUser = $this->eloquentUser->find($primaryKey);

        $user = $this->toEntity($eloquentUser);

        return $user;
    }

    public function userOfEmail($email)
    {
        $eloquentUser = $this->eloquentUser->where('email', $email)->first();

        $user = $this->toEntity($eloquentUser);

        return $user;
    }

    public function remove(User $user)
    {
        $eloquentUser = $this->toEloquent($user);

        $eloquentUser->delete();

        return true;
    }

    public function save(User $user)
    {
        $eloquentUser = $this->toEloquent($user);

        $eloquentUser->save();

        $eloquentUser->revokeAllRoles();
        $roleIds = $user->roleIds();
        if (!empty($roleIds)) {
            $roleIdIds = [];
            foreach ($roleIds as $roleId) {
                $roleIdIds[] = $roleId->id();
            }
            $eloquentUser->assignRole($roleIdIds);
        }

        $user = $this->toEntity($eloquentUser);

        return $user;
    }

    public function is(User $user, RoleSlug $roleSlug, $operator = null)
    {
        $eloquentUser = $this->toEloquent($user);

        return $eloquentUser->is($roleSlug->value(), $operator);
    }

    public function can(User $user, $permission, $operator = null)
    {
        $eloquentUser = $this->toEloquent($user);

        return $eloquentUser->can($permission, $operator);
    }

    public function identityName()
    {
        return 'id';
    }

    public function rememberTokenName()
    {
        return 'remember_token';
    }

    public function toEntity(EloquentUser $eloquentUser)
    {
        $userId = new UserId($eloquentUser->id);
        $name = $eloquentUser->name;
        $email = $eloquentUser->email;
        $password = $eloquentUser->password;
        $apiToken = $eloquentUser->api_token;
        $eloquentRoles = $eloquentUser->roles;
        $roleIds = [];
        foreach ($eloquentRoles as $eloquentRole) {
            $roleIds[] = new RoleId($eloquentRole->id);
        }
        $createdAt = $eloquentUser->created_at;
        $updatedAt = $eloquentUser->updated_at;

        $user = new User(
            $userId,
            $name,
            $email,
            $password,
            $apiToken,
            $roleIds,
            $createdAt,
            $updatedAt
        );

        return $user;
    }

    public function toEloquent(User $user)
    {
        $primaryKey = $user->userId()->id();

        if (is_null($primaryKey)) {
            $eloquentUser = new EloquentUser();
        } else {
            $eloquentUser = $this->eloquentUser->find($primaryKey);
        }

        $eloquentUser->name = $user->name();
        $eloquentUser->email = $user->email();
        $eloquentUser->password = $user->password();
        $eloquentUser->api_token = $user->apiToken();

        return $eloquentUser;
    }
}
