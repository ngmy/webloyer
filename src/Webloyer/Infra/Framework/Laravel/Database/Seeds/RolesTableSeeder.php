<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webloyer\Infra\Persistence\Eloquent\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            if (Role::count() > 0) {
                return;
            }

            Role::create([
                'name'        => 'Administrator',
                'slug'        => 'administrator',
                'description' => 'Manage administration privileges.',
            ]);

            Role::create([
                'name'        => 'Developer',
                'slug'        => 'developer',
                'description' => 'Manage developer privileges.',
            ]);

            Role::create([
                'name'        => 'Operator',
                'slug'        => 'operator',
                'description' => 'Manage operator privileges.',
            ]);
        });
    }
}
