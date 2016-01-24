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
        ]);
    }
}
