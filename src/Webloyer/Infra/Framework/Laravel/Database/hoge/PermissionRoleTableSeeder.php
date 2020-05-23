<?php

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

            $moderatorRole = Role::where('name', 'Moderator')
                ->where('slug', 'moderator')
                ->first();
            $moderatorRole->assignPermission([
                'deployment',
                'project.moderator',
                'recipe.moderator',
                'server.moderator',
                'setting.moderator',
                'user.moderator',
            ]);
        });
    }
}
