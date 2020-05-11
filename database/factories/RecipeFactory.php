<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webloyer\Infra\Persistence\Eloquent\Models\Recipe;

$factory->define(Recipe::class, function (Faker $faker) {
    return [
        'name' => '',
        'description' => '',
        'body' => '',
    ];
});
