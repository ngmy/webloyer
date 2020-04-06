<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Kodeine\Acl\Models\Eloquent\Role;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'slug' => $faker->unique()->name,
        'description' => '',
    ];
});
