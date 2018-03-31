<?php

namespace Ngmy\Webloyer\IdentityAccess\Application\Role;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleRepositoryInterface;

class RoleService
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles()
    {
        return $this->roleRepository->allRoles();
    }

    public function getRoleBySlug($roleSlug)
    {
        return $this->roleRepository->roleOfSlug(new RoleSlug($roleSlug));
    }
}
