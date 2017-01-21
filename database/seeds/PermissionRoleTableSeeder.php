<?php

use Illuminate\Database\Seeder;
use Kodeine\Acl\Models\Eloquent\Role;

class PermissionRoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('permission_role')->delete();

        $roleAdmin = Role::where('name', 'Administrator')
            ->where('slug', 'administrator')
            ->first();
        $roleAdmin->assignPermission([
            'project',
            'deployment',
            'recipe',
            'server',
            'user',
            'setting',
        ]);

        $roleDeveloper = Role::where('name', 'Developer')
            ->where('slug', 'developer')
            ->first();
        $roleDeveloper->assignPermission([
            'project',
            'deployment',
            'recipe',
            'server',
            'user.developer',
            'setting.developer',
        ]);

        $roleModerator = Role::where('name', 'Moderator')
            ->where('slug', 'moderator')
            ->first();
        $roleModerator->assignPermission([
            'project.moderator',
            'deployment',
            'recipe.moderator',
            'server.moderator',
            'user.moderator',
            'setting.moderator',
        ]);

        $roleModerator = Role::where('name', 'Product Owner')
            ->where('slug', 'p-o')
            ->first();
        $roleModerator->assignPermission([
            'project.p-o',
            'deployment',
            'recipe.p-o',
            'server.p-o',
            'user.p-o',
            'setting.p-o',
        ]);
    }
}
