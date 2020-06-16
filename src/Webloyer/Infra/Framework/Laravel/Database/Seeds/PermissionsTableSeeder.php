<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webloyer\Infra\Persistence\Eloquent\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function (): void {
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
                'name'        => 'project.operator',
                'slug'        => [
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $projectPermission->getKey(),
                'description' => 'Operator project permissions.',
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
                'name'        => 'recipe.operator',
                'slug'        => [
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $recipePermission->getKey(),
                'description' => 'Operator recipe permissions.',
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
                'name'        => 'server.operator',
                'slug'        => [
                    'create' => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $serverPermission->getKey(),
                'description' => 'Operator server permissions.',
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
                'name'        => 'setting.operator',
                'slug'        => [
                    'create' => false,
                    'view'   => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $settingPermission->getKey(),
                'description' => 'Operator setting permissions.',
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
                'name'        => 'user.operator',
                'slug'        => [
                    'create' => false,
                    'view'   => false,
                    'update' => false,
                    'delete' => false,
                ],
                'inherit_id'  => $userPermission->getKey(),
                'description' => 'Operator user permissions.',
            ]);
        });
    }
}
