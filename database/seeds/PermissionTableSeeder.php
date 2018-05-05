<?php

use Illuminate\Database\Seeder;
use Kodeine\Acl\Models\Eloquent\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run()
    {
        Permission::orderBy('inherit_id', 'desc')->delete();

        // project
        $permission = new Permission();
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

        $permission = new Permission();
        $permissionProjectOperator = $permission->create([
            'name'        => 'project.operator',
            'slug'        => [
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'inherit_id'  => $permissionProject->getKey(),
            'description' => 'Operator project permissions.',
        ]);

        // deployment
        $permission = new Permission();
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
        $permission = new Permission();
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

        $permission = new Permission();
        $permissionRecipeOperator = $permission->create([
            'name'        => 'recipe.operator',
            'slug'        => [
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'inherit_id'  => $permissionRecipe->getKey(),
            'description' => 'Operator recipe permissions.',
        ]);

        // server
        $permission = new Permission();
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

        $permission = new Permission();
        $permissionServerOperator = $permission->create([
            'name'        => 'server.operator',
            'slug'        => [
                'create' => false,
                'update' => false,
                'delete' => false,
            ],
            'inherit_id'  => $permissionServer->getKey(),
            'description' => 'Operator server permissions.',
        ]);

        // user
        $permission = new Permission();
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

        $permission = new Permission();
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

        $permission = new Permission();
        $permissionUserOperator = $permission->create([
            'name'        => 'user.operator',
            'slug'        => [
                'create' => false,
                'view'   => false,
                'update' => false,
                'delete' => false,
            ],
            'inherit_id'  => $permissionUser->getKey(),
            'description' => 'Operator user permissions.',
        ]);

        // setting
        $permission = new Permission();
        $permissionSetting = $permission->create([
            'name'        => 'setting',
            'slug'        => [
                'create' => true,
                'view'   => true,
                'update' => true,
                'delete' => true,
            ],
            'description' => 'Manage setting permissions.',
        ]);

        $permission = new Permission();
        $permissionSettingDeveloper = $permission->create([
            'name'        => 'setting.developer',
            'slug'        => [
                'create' => false,
                'view'   => false,
                'update' => false,
                'delete' => false,
            ],
            'inherit_id'  => $permissionSetting->getKey(),
            'description' => 'Developer setting permissions.',
        ]);

        $permission = new Permission();
        $permissionSettingOperator = $permission->create([
            'name'        => 'setting.operator',
            'slug'        => [
                'create' => false,
                'view'   => false,
                'update' => false,
                'delete' => false,
            ],
            'inherit_id'  => $permissionSetting->getKey(),
            'description' => 'Operator setting permissions.',
        ]);
    }
}
