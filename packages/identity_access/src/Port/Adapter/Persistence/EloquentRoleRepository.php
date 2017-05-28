<?php

namespace Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence;

use Kodeine\Acl\Models\Eloquent\Role as EloquentRole;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\Role;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleRepositoryInterface;

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
            return $this->toEntity($eloquentRole);
        })->all();

        return $roles;
    }

    public function roleOfId(RoleId $roleId)
    {
        $primaryKey = $roleId->id();

        $eloquentRole = $this->eloquentRole->find($primaryKey);

        $role = $this->toEntity($eloquentRole);

        return $role;
    }

    public function roleOfSlug(RoleSlug $roleSlug)
    {
        $eloquentRole = $this->eloquentRole->where('slug', $roleSlug->value())->first();

        $role = $this->toEntity($eloquentRole);

        return $role;
    }

    private function toEntity(EloquentRole $eloquentRole)
    {
        $roleId = new RoleId($eloquentRole->id);
        $name = $eloquentRole->name;
        $roleSlug = new RoleSlug($eloquentRole->slug);
        $description = $eloquentRole->description;
        $createdAt = $eloquentRole->created_at;
        $updatedAt = $eloquentRole->updated_at;

        $role = new Role(
            $roleId,
            $name,
            $roleSlug,
            $description,
            $createdAt,
            $updatedAt
        );

        return $role;
    }

    private function toEloquent(Role $role)
    {
        $primaryKey = $role->roleId()->id();

        if (is_null($primaryKey)) {
            $eloquentRole = new EloquentRole();
        } else {
            $eloquentRole = $this->eloquentRole->find($primaryKey);
        }

        $eloquentRole->name = $role->name();
        $eloquentRole->slug = $role->slug()->value();
        $eloquentRole->description = $role->description();

        return $eloquentRole;
    }
}
