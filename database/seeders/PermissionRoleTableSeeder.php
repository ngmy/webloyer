<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Support\Facades\DB;

/**
 * Class PermissionRoleTableSeeder
 * @package Database\Seeders
 */
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
    }
}
