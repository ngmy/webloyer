<?php

namespace Ngmy\Webloyer\IdentityAccess\Application\Role;

use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\Role;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleSlug;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleRepositoryInterface;

class RoleService
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getAllRoles(): array
    {
        return $this->roleRepository->allRoles();
    }

    public function getRoleBySlug(string $roleSlug): ?Role
    {
        return $this->roleRepository->roleOfSlug(new RoleSlug($roleSlug));
    }
}
