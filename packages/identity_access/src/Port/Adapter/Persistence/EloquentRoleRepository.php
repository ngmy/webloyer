<?php

namespace Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleRepositoryInterface;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\Role as EloquentRole;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    private $eloquentRole;

    /**
     * Create a new repository instance.
     *
     * @param \Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\Role $eloquentRole
     * @return void
     */
    public function __construct(EloquentRole $eloquentRole)
    {
        $this->eloquentRole = $eloquentRole;
    }

    public function allRoles()
    {
        $eloquentRoles = $this->eloquentRole->all();

        $roles = $eloquentRoles->map(function ($eloquentRole, $key) {
            return $eloquentRole->toEntity();
        })->all();

        return $roles;
    }

    public function roleOfId(RoleId $roleId)
    {
        $primaryKey = $roleId->id();

        $eloquentRole = $this->eloquentRole->find($primaryKey);

        $role = $eloquentRole->toEntity();

        return $role;
    }

    public function roleOfSlug(RoleSlug $roleSlug)
    {
        $eloquentRole = $this->eloquentRole->where('slug', $roleSlug->value())->first();

        $role = $eloquentRole->toEntity();

        return $role;
    }
}
