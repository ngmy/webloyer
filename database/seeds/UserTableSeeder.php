<?php

use Illuminate\Database\Seeder;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\Eloquent\User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'name'     => 'admin',
            'email'    => 'admin@example.com',
            'password' => Hash::make('admin'),
        ]);
    }
}
