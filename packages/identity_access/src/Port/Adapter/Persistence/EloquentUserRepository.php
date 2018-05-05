<?php

namespace Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;
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
            return $eloquentUser->toEntity();
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
                return $eloquentUser->toEntity();
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

        $user = $eloquentUser->toEntity();

        return $user;
    }

    public function userOfEmail($email)
    {
        $eloquentUser = $this->eloquentUser->where('email', $email)->first();

        $user = $eloquentUser->toEntity();

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
            $eloquentUser->assignRole(array_map(function ($roleId) {
                return $roleId->id();
            }, $roleIds));
        }

        $user = $eloquentUser->toEntity();

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
