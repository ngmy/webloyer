<?php

Route::namespace('V1')->prefix('v1')->group(function () {
    Route::middleware('github_webhook_secret')->namespace('GitHub')->prefix('github')->group(function () {
        Route::resource('projects.deployments', 'DeploymentController')->only([
            'store',
        ]);
    });
});
