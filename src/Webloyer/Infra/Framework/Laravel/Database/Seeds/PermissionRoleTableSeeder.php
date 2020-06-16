<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webloyer\Infra\Persistence\Eloquent\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            if (DB::table('permission_role')->count() > 0) {
                return;
            }

            $adminRole = Role::where('name', 'Administrator')
                ->where('slug', 'administrator')
                ->first();
            assert(!is_null($adminRole));
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
            assert(!is_null($developerRole));
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
            assert(!is_null($operatorRole));
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
