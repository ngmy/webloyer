<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\MaxDeployment;
use Faker\Generator as Faker;

$factory->define(MaxDeployment::class, function (Faker $faker) {
    return [
        'project_id' => null,
        'number' => 0,
    ];
});
