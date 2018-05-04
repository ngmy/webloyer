<?php

use Illuminate\Database\Seeder;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\Role;

class RoleTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->delete();

        $role = new Role();
        $roleAdmin = $role->create([
            'name'        => 'Administrator',
            'slug'        => 'administrator',
            'description' => 'Manage administration privileges.',
        ]);

        $role = new Role();
        $roleDeveloper = $role->create([
            'name'        => 'Developer',
            'slug'        => 'developer',
            'description' => 'Manage developer privileges.',
        ]);

        $role = new Role();
        $roleOperator = $role->create([
            'name'        => 'Operator',
            'slug'        => 'operator',
            'description' => 'Manage operator privileges.',
        ]);
    }
}
