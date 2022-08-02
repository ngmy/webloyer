<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Kodeine\Acl\Models\Eloquent\Role;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleTableSeeder
 * @package Database\Seeders
 */
class RoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->delete();

        $role = new Role;
        $roleAdmin = $role->create([
            'name'        => 'Administrator',
            'slug'        => 'administrator',
            'description' => 'Manage administration privileges.',
        ]);

        $role = new Role;
        $roleDeveloper = $role->create([
            'name'        => 'Developer',
            'slug'        => 'developer',
            'description' => 'Manage developer privileges.',
        ]);

        $role = new Role;
        $roleModerator = $role->create([
            'name'        => 'Moderator',
            'slug'        => 'moderator',
            'description' => 'Manage moderator privileges.',
        ]);
    }
}
