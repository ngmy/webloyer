<?php

declare(strict_types=1);

namespace Webloyer\Query;

use Kodeine\Acl\Models\Eloquent\Role;
use Webloyer\Domain\Model\Role\RoleView;

class AllRolesQueryHandler
{
    /**
     * @param AllRolesQuery $query
     * @return array<int, RoleView>
     */
    public function handle(AllRolesQuery $query): array
    {
        return Role::all()
            ->map(function (Role $role): RoleView {
                return new RoleView($role->slug, $role->name);
            })
            ->toArray();
    }
}
