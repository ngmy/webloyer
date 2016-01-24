<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Kodeine\Acl\Models\Eloquent\RoleUser;

class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('role_user')->delete();

        $user = User::where('email', 'admin@example.com')->first();
        $user->assignRole('administrator');
    }
}
