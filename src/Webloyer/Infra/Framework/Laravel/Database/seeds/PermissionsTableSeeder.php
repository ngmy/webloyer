<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Kodeine\Acl\Models\Eloquent\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        DB::transaction(function () {
            if (Permission::count() > 0) {
                return;
            }

            // Deployment permission
            Permission::create([
                'name'        => 'deployment',
                'slug'        => [
                    'create' => true,
                    'view'   => true,
                    'update' => true,
                    'delete' => true,
                ],
                'description' => 'Manage deployment permissions.',
            ]);

            // Project permission
            $projectPermission = Permission::create([
                'name'        => 'project',
                'slug'        => [
                    'create' => true,
                    'view'   => true,
                    'update' => true,
                    'delete' => true,
                ],
                'description' => 'Manage project permissions.',
            ]);
            Permission::create([
                'name'        => 'project.moderator',
                'slug'        => [
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $projectPermission->getKey(),
                'description' => 'Moderator project permissions.',
            ]);

            // Recipe permission
            $recipePermission = Permission::create([
                'name'        => 'recipe',
                'slug'        => [
                    'create' => true,
                    'view'   => true,
                    'update' => true,
                    'delete' => true,
                ],
                'description' => 'Manage recipe permissions.',
            ]);
            Permission::create([
                'name'        => 'recipe.moderator',
                'slug'        => [
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $recipePermission->getKey(),
                'description' => 'Moderator recipe permissions.',
            ]);

            // Server permission
            $serverPermission = Permission::create([
                'name'        => 'server',
                'slug'        => [
                    'create' => true,
                    'view'   => true,
                    'update' => true,
                    'delete' => true,
                ],
                'description' => 'Manage server permissions.',
            ]);
            Permission::create([
                'name'        => 'server.moderator',
                'slug'        => [
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $serverPermission->getKey(),
                'description' => 'Moderator server permissions.',
            ]);

            // Setting permission
            $settingPermission = Permission::create([
                'name'        => 'setting',
                'slug'        => [
                    'create' => true,
                    'view'   => true,
                    'update' => true,
                    'delete' => true,
                ],
                'description' => 'Manage setting permissions.',
            ]);
            Permission::create([
                'name'        => 'setting.developer',
                'slug'        => [
                    'create' => false,
                    'view'   => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $settingPermission->getKey(),
                'description' => 'Developer setting permissions.',
            ]);
            Permission::create([
                'name'        => 'setting.moderator',
                'slug'        => [
                    'create' => false,
                    'view'   => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $settingPermission->getKey(),
                'description' => 'Moderator setting permissions.',
            ]);

            // User permission
            $userPermission = Permission::create([
                'name'        => 'user',
                'slug'        => [
                    'create' => true,
                    'view'   => true,
                    'update' => true,
                    'delete' => true,
                ],
                'description' => 'Manage user permissions.',
            ]);
            Permission::create([
                'name'        => 'user.developer',
                'slug'        => [
                    'create' => false,
                    'view'   => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $userPermission->getKey(),
                'description' => 'Developer user permissions.',
            ]);
            Permission::create([
                'name'        => 'user.moderator',
                'slug'        => [
                    'create' => false,
                    'view'   => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $userPermission->getKey(),
                'description' => 'Moderator user permissions.',
            ]);
        });
    }
}
