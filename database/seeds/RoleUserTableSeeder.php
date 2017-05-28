<?php

use Illuminate\Database\Seeder;
use Kodeine\Acl\Models\Eloquent\RoleUser;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\User;

class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('role_user')->delete();

        $user = User::where('email', 'admin@example.com')->first();
        $user->assignRole('administrator');
    }
}
