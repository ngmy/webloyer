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

        $roleOperator = Role::where('name', 'Operator')
            ->where('slug', 'operator')
            ->first();
        $roleOperator->assignPermission([
            'project.operator',
            'deployment',
            'recipe.operator',
            'server.operator',
            'user.operator',
            'setting.operator',
        ]);
    }
}
