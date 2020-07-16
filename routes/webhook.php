<?php

Route::group(['prefix' => 'github/v1', 'middleware' => 'github_webhook_secret'], function () {
    Route::resource('projects.deployments', 'Webhook\Github\V1\DeploymentsController', [
        'only' => [
            'store',
        ],
        'parameters' => [
            'projects' => 'project',
            'deployments' => 'deployment',
        ],
    ]);
});
