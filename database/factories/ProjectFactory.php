<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Webloyer\Infra\Persistence\Eloquent\Models\Project;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'stage' => 'staging',
        'server_id' => null,
        'repository' => $faker->url,
        'email_notification_recipient' => $faker->safeEmail,
        'attributes' => [],
        'days_to_keep_deployments' => 3,
        'max_number_of_deployments_to_keep' => 10,
        'keep_last_deployment' => false,
        'github_webhook_secret' => $faker->password,
        'github_webhook_user_id' => null,
    ];
});
