<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webloyer\Infra\Persistence\Eloquent\Models\Deployment;

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
