<?php

use Illuminate\Database\Seeder;
use Kodeine\Acl\Models\Eloquent\Permission;

class PermissionTableSeeder extends Seeder {

	public function run()
	{
		Permission::orderBy('inherit_id', 'desc')->delete();

		// project
		$permission = new Permission;
		$permissionProject = $permission->create([
			'name'        => 'project',
			'slug'        => [
				'create' => true,
				'view'   => true,
				'update' => true,
				'delete' => true,
			],
			'description' => 'Manage project permissions.',
		]);

		$permission = new Permission;
		$permissionProjectModerator = $permission->create([
			'name'        => 'project.moderator',
			'slug'        => [
				'create' => false,
				'update' => false,
				'delete' => false,
			],
			'inherit_id'  => $permissionProject->getKey(),
			'description' => 'Moderator project permissions.',
		]);

		// deployment
		$permission = new Permission;
		$permissionDeployment = $permission->create([
			'name'        => 'deployment',
			'slug'        => [
				'create' => true,
				'view'   => true,
				'update' => true,
				'delete' => true,
			],
			'description' => 'Manage deployment permissions.',
		]);

		// recipe
		$permission = new Permission;
		$permissionRecipe = $permission->create([
			'name'        => 'recipe',
			'slug'        => [
				'create' => true,
				'view'   => true,
				'update' => true,
				'delete' => true,
			],
			'description' => 'Manage recipe permissions.',
		]);

		$permission = new Permission;
		$permissionRecipeModerator = $permission->create([
			'name'        => 'recipe.moderator',
			'slug'        => [
				'create' => false,
				'update' => false,
				'delete' => false,
			],
			'inherit_id'  => $permissionRecipe->getKey(),
			'description' => 'Moderator recipe permissions.',
		]);

		// server
		$permission = new Permission;
		$permissionServer = $permission->create([
			'name'        => 'server',
			'slug'        => [
				'create' => true,
				'view'   => true,
				'update' => true,
				'delete' => true,
			],
			'description' => 'Manage server permissions.',
		]);

		$permission = new Permission;
		$permissionServerModerator = $permission->create([
			'name'        => 'server.moderator',
			'slug'        => [
				'create' => false,
				'update' => false,
				'delete' => false,
			],
			'inherit_id'  => $permissionServer->getKey(),
			'description' => 'Moderator server permissions.',
		]);

		// user
		$permission = new Permission;
		$permissionUser = $permission->create([
			'name'        => 'user',
			'slug'        => [
				'create' => true,
				'view'   => true,
				'update' => true,
				'delete' => true,
			],
			'description' => 'Manage user permissions.',
		]);

		$permission = new Permission;
		$permissionUserDeveloper = $permission->create([
			'name'        => 'user.developer',
			'slug'        => [
				'create' => false,
				'view'   => false,
				'update' => false,
				'delete' => false,
			],
			'inherit_id'  => $permissionUser->getKey(),
			'description' => 'Developer user permissions.',
		]);

		$permission = new Permission;
		$permissionUserModerator = $permission->create([
			'name'        => 'user.moderator',
			'slug'        => [
				'create' => false,
				'view'   => false,
				'update' => false,
				'delete' => false,
			],
			'inherit_id'  => $permissionUser->getKey(),
			'description' => 'Moderator user permissions.',
		]);
	}

}
