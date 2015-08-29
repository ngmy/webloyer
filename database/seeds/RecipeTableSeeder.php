<?php

use Illuminate\Database\Seeder;
use App\Models\Recipe;

class RecipeTableSeeder extends Seeder {

	public function run()
	{
		DB::table('recipes')->delete();

		$deployerRecipes = [
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
			'symfony' => [
				'name'        => 'deployer-symfony-recipe',
				'description' => 'This recipe is specifically for deploying Symfony projects.',
				'body'        => <<<EOF
<?php
require 'recipe/symfony.php';
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

		foreach ($deployerRecipes as $recipe) {
			Recipe::create([
				'name'        => $recipe['name'],
				'description' => $recipe['description'],
				'body'        => $recipe['body'],
			]);
		}
	}

}
