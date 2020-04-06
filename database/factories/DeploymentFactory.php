<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Deployment;
use Faker\Generator as Faker;

$factory->define(Deployment::class, function (Faker $faker) {
    return [
        'project_id' => null,
        'number' => $faker->unique()->randomNumber,
        'task' => 'deploy',
        'status' => null,
        'message' => null,
        'user_id' => null,
    ];
});
