<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder {

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
