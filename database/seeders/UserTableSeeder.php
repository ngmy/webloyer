<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserTableSeeder
 * @package Database\Seeders
 */
class UserTableSeeder extends Seeder
{
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
