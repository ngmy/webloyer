<?php

namespace Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent;

use Kodeine\Acl\Models\Eloquent\Role as BaseRole;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\Role as EntityRole;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;

class Role extends BaseRole
{
    /**
     * Convert an Eloquent object into an entity.
     *
     * @return \Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\Role
     */
    public function toEntity()
    {
        return new EntityRole(
            new RoleId($this->id),
            $this->name,
            new RoleSlug($this->slug),
            $this->description,
            $this->created_at,
            $this->updated_at
        );
    }
}
