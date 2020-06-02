<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Kodeine\Acl\Models\Eloquent\Role;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            if (DB::table('permission_role')->count() > 0) {
                return;
            }

            $adminRole = Role::where('name', 'Administrator')
                ->where('slug', 'administrator')
                ->first();
            $adminRole->assignPermission([
                'deployment',
                'project',
                'recipe',
                'server',
                'setting',
                'user',
            ]);

            $developerRole = Role::where('name', 'Developer')
                ->where('slug', 'developer')
                ->first();
            $developerRole->assignPermission([
                'deployment',
                'project',
                'recipe',
                'server',
                'setting.developer',
                'user.developer',
            ]);

            $operatorRole = Role::where('name', 'Operator')
                ->where('slug', 'operator')
                ->first();
            $operatorRole->assignPermission([
                'deployment',
                'project.operator',
                'recipe.operator',
                'server.operator',
                'setting.operator',
                'user.operator',
            ]);
        });
    }
}
