<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Class RoleUserTableSeeder
 * @package Database\Seeders
 */
class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('role_user')->delete();

        $user = User::where('email', 'admin@example.com')->first();
        $user->assignRole('administrator');
    }
}
