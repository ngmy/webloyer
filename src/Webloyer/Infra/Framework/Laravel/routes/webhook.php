<?php

Route::namespace('V1')->prefix('v1')->group(function () {
    Route::middleware('github_webhook_secret')->namespace('GitHub')->prefix('github')->group(function () {
        Route::namespace('Deployment')->group(function () {
            Route::post('projects/{project}/deployments/deploy', 'DeployController')->name('webhook.v1.github.projects.deployments.deploy');
            Route::post('projects/{project}/deployments/rollback', 'RollbackController')->name('webhook.v1.github.projects.deployments.rollback');
        });
    });
});
