<?php

namespace App\Providers;

use App\Services\Deployment\DeployerFile;
use App\Services\Deployment\DeployerDeploymentFileBuilder;
use App\Services\Deployment\DeployerRecipeFileBuilder;
use App\Services\Deployment\DeployerServerListFileBuilder;
use App\Services\Notification\MailNotifier;
use App\Services\Filesystem\LaravelFilesystem;
use App\Services\Api\JsonRpc;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );

        $this->app->bind('App\Services\Notification\NotifierInterface', function ($app) {
            return new MailNotifier();
        });

        $this->app->bind('App\Services\Deployment\DeployerServerListFileBuilder', function ($app) {
            return new DeployerServerListFileBuilder(
                new LaravelFilesystem($app['files']),
                new DeployerFile(),
                new Parser(),
                new Dumper()
            );
        });
        $this->app->bind('App\Services\Deployment\DeployerRecipeFileBuilder', function ($app) {
            return new DeployerRecipeFileBuilder(
                new LaravelFilesystem($app['files']),
                new DeployerFile()
            );
        });
        $this->app->bind('App\Services\Deployment\DeployerDeploymentFileBuilder', function ($app) {
            return new DeployerDeploymentFileBuilder(
                new LaravelFilesystem($app['files']),
                new DeployerFile()
            );
        });

        $this->app->bind('App\Services\Api\JsonRpc', function ($app) {
            return new JsonRpc(
                $app->make('App\Repositories\Project\ProjectInterface'),
                $app->make('App\Services\Form\Deployment\DeploymentForm')
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
