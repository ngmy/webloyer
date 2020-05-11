<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webloyer\Infra\Persistence\Eloquent\Models\MaxDeployment;

$factory->define(MaxDeployment::class, function (Faker $faker) {
    return [
        'project_id' => null,
        'number' => 0,
    ];
});
