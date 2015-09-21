<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('UserTableSeeder');
		$this->call('RecipeTableSeeder');
		$this->call('RoleTableSeeder');
		$this->call('PermissionTableSeeder');
		$this->call('PermissionRoleTableSeeder');
		$this->call('RoleUserTableSeeder');
	}

}
