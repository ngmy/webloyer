<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webloyer\Infra\Persistence\Eloquent\Models\Server;

$factory->define(Server::class, function (Faker $faker) {
    return [
        'name' => '',
        'description' => '',
        'body' => '',
    ];
});
