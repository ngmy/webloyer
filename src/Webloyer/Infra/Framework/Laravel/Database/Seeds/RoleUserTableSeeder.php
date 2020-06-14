<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webloyer\Infra\Persistence\Eloquent\Models\User;

class RoleUserTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            if (DB::table('role_user')->count() > 0){
                return;
            }

            $user = User::where('email', 'admin@example.com')->first();
            assert(!is_null($user));
            $user->assignRole('administrator');
        });
    }
}
