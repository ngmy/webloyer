<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\Database\Seeds;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webloyer\Infra\Domain\Model\Recipe\EloquentRecipeRepository;
use Webloyer\Infra\Persistence\Eloquent\Models\Recipe;

class RecipesTableSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run(): void
    {
        DB::transaction(function (): void {
            if (Recipe::count() > 0) {
                return;
            }

            $recipes = $this->getRecipes();
            $recipeRepository = app(EloquentRecipeRepository::class);
            foreach ($recipes as $recipe) {
                Recipe::create([
                    'uuid'        => $recipeRepository->nextId()->value(),
                    'name'        => $recipe['name'],
                    'description' => $recipe['description'],
                    'body'        => $recipe['body'],
                ]);
            }
        });
    }

    /**
     * @return array<string, array{name: string, description: string, body: string}>
     */
    private function getRecipes(): array
    {
        return [
            'cakephp' => [
                'name'        => 'deployer-cakephp-recipe',
                'description' => 'This recipe is specifically for deploying CakePHP 3 projects.',
                'body'        => <<<EOF
<?php
require 'recipe/cakephp.php';
EOF
                ,
            ],
            'codeigniter' => [
                'name'        => 'deployer-codeigniter-recipe',
                'description' => 'This recipe is specifically for deploying CodeIgniter projects.',
                'body'        => <<<EOF
<?php
require 'recipe/codeigniter.php';
EOF
                ,
            ],
            'common' => [
                'name'        => 'deployer-common-recipe',
                'description' => 'This recipe is the basis of all other recipes',
                'body'        => <<<EOF
<?php
require 'recipe/common.php';
EOF
                ,
            ],
            'composer' => [
                'name'        => 'deployer-composer-recipe',
                'description' => 'This recipe is specifically for deploying projects which uses composer.',
                'body'        => <<<EOF
<?php
require 'recipe/composer.php';
EOF
                ,
            ],
            'drupal7' => [
                'name'        => 'deployer-drupal7-recipe',
                'description' => 'This recipe is specifically for deploying Drupal 7 projects.',
                'body'        => <<<EOF
<?php
require 'recipe/drupal7.php';
EOF
                ,
            ],
            'drupal8' => [
                'name'        => 'deployer-drupal8-recipe',
                'description' => 'This recipe is specifically for deploying Drupal 8 projects.',
                'body'        => <<<EOF
<?php
require 'recipe/drupal8.php';
EOF
                ,
            ],
            'fuelphp' => [
                'name'        => 'deployer-fuelphp-recipe',
                'description' => 'This recipe is specifically for deploying FuelPHP projects.',
                'body'        => <<<EOF
<?php
require 'recipe/fuelphp.php';
EOF
                ,
            ],
            'laravel' => [
                'name'        => 'deployer-laravel-recipe',
                'description' => 'This recipe is specifically for deploying Laravel projects.',
                'body'        => <<<EOF
<?php
require 'recipe/laravel.php';
EOF
                ,
            ],
            'magento' => [
                'name'        => 'deployer-magento-recipe',
                'description' => 'This recipe is specifically for deploying Magento projects.',
                'body'        => <<<EOF
<?php
require 'recipe/magento.php';
EOF
                ,
            ],
            'symfony' => [
                'name'        => 'deployer-symfony-recipe',
                'description' => 'This recipe is specifically for deploying Symfony projects.',
                'body'        => <<<EOF
<?php
require 'recipe/symfony.php';
EOF
                ,
            ],
            'symfony3' => [
                'name'        => 'deployer-symfony3-recipe',
                'description' => 'This recipe is specifically for deploying Symfony 3 projects.',
                'body'        => <<<EOF
<?php
require 'recipe/symfony3.php';
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
            'yii' => [
                'name'        => 'deployer-yii-recipe',
                'description' => 'This recipe is specifically for deploying Yii projects.',
                'body'        => <<<EOF
<?php
require 'recipe/yii.php';
EOF
                ,
            ],
            'yii2-app-advanced' => [
                'name'        => 'deployer-yii2-app-advanced-recipe',
                'description' => 'This recipe is specifically for deploying Yii 2 Advanced Project Template projects.',
                'body'        => <<<EOF
<?php
require 'recipe/yii2-app-advanced.php';
EOF
                ,
            ],
            'yii2-app-basic' => [
                'name'        => 'deployer-yii2-app-basic-recipe',
                'description' => 'This recipe is specifically for deploying Yii 2 Basic Project Template projects.',
                'body'        => <<<EOF
<?php
require 'recipe/yii2-app-basic.php';
EOF
                ,
            ],
            'zend_framework' => [
                'name'        => 'deployer-zend_framework-recipe',
                'description' => 'This recipe is specifically for deploying Zend Framework projects.',
                'body'        => <<<EOF
<?php
require 'recipe/zend_framework.php';
EOF
                ,
            ],
        ];
    }
}
