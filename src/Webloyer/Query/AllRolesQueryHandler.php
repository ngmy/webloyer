<?php

declare(strict_types=1);

namespace Webloyer\Query;

use Webloyer\Domain\Model\Role\RoleView;
use Webloyer\Infra\Persistence\Eloquent\Models\Role;

class AllRolesQueryHandler
{
    /**
     * @param AllRolesQuery $query
     * @return list<RoleView>
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
