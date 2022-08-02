<?php
declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Recipe;
use Illuminate\Support\Facades\DB;

/**
 * Class RecipeTableSeeder
 * @package Database\Seeders
 */
class RecipeTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('recipes')->delete();

        $deployerRecipes = [
            'magento2' => [
                'name'        => 'deployer-magento-recipe',
                'description' => 'This recipe is specifically for deploying Magento2 projects.',
                'body'        => <<<EOF
<?php
require 'recipe/magento2.php';
EOF
                ,
            ],
            'wordpress' => [
                'name'        => 'deployer-wordpress-recipe',
                'description' => 'This recipe is specifically for deploying WordPress projects.',
                'body'        => <<<EOF
<?php
require 'recipe/wordpress.php';
EOF
                ,
            ],
        ];

        foreach ($deployerRecipes as $recipe) {
            Recipe::create([
                'name'        => $recipe['name'],
                'description' => $recipe['description'],
                'body'        => $recipe['body'],
            ]);
        }
    }
}
