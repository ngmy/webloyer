<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{
    DB,
    Hash,
};
use Illuminate\Support\Str;
use Webloyer\Infra\Persistence\Eloquent\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            if (User::count() > 0) {
                return;
            }

            User::create([
                'name'      => 'admin',
                'email'     => 'admin@example.com',
                'password'  => Hash::make('admin'),
                'api_token' => Str::random(60),
            ]);
        });
    }
}
