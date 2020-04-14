<?php

Route::middleware('github_webhook_secret')->namespace('Github')->prefix('github')->group(function () {
    Route::namespace('V1')->prefix('v1')->group(function () {
        Route::resource('projects.deployments', 'DeploymentController')->only([
            'store',
        ]);
    });
});
