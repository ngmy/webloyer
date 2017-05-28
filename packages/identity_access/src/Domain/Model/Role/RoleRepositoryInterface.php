<?php

namespace Ngmy\Webloyer\IdentityAccess\Domain\Model\Role;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\Role;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleId;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;

interface RoleRepositoryInterface
{
    public function allRoles();

    public function roleOfId(RoleId $roleId);

    public function roleOfSlug(RoleSlug $roleSlug);
}

