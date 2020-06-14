<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Database\Seeds;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $this->call([
//            UsersTableSeeder::class, // TODO
            RolesTableSeeder::class,
            PermissionsTableSeeder::class,
//            RoleUserTableSeeder::class, // TODO
            PermissionRoleTableSeeder::class,
            RecipesTableSeeder::class,
        ]);
    }
}
