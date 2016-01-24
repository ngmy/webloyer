<?php

namespace App\Providers;

use App\Console\Commands\Deploy;
use App\Console\Commands\Rollback;
use App\Services\Deployment\QueueDeployCommander;
use App\Services\Form\Project\ProjectForm;
use App\Services\Form\Project\ProjectFormLaravelValidator;
use App\Services\Form\Deployment\DeploymentForm;
use App\Services\Form\Deployment\DeploymentFormLaravelValidator;
use App\Services\Form\Recipe\RecipeForm;
use App\Services\Form\Recipe\RecipeFormLaravelValidator;
use App\Services\Form\Server\ServerForm;
use App\Services\Form\Server\ServerFormLaravelValidator;
use App\Services\Form\User\UserForm;
use App\Services\Form\User\UserFormLaravelValidator;
use App\Services\Notification\MailNotifier;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Process\ProcessBuilder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );

        $this->app->bind('App\Services\Deployment\DeployCommanderInterface', function ($app) {
            return new QueueDeployCommander(
                $app->make('Illuminate\Contracts\Bus\Dispatcher')
            );
        });

        $this->app->bind('App\Services\Form\Project\ProjectForm', function ($app) {
            return new ProjectForm(
                new ProjectFormLaravelValidator($app['validator']),
                $app->make('App\Repositories\Project\ProjectInterface')
            );
        });

        $this->app->bind('App\Services\Form\Deployment\DeploymentForm', function ($app) {
            return new DeploymentForm(
                new DeploymentFormLaravelValidator($app['validator']),
                $app->make('App\Repositories\Deployment\DeploymentInterface'),
                $app->make('App\Services\Deployment\DeployCommanderInterface')
            );
        });

        $this->app->bind('App\Services\Form\Recipe\RecipeForm', function ($app) {
            return new RecipeForm(
                new RecipeFormLaravelValidator($app['validator']),
                $app->make('App\Repositories\Recipe\RecipeInterface')
            );
        });

        $this->app->bind('App\Services\Form\Server\ServerForm', function ($app) {
            return new ServerForm(
                new ServerFormLaravelValidator($app['validator']),
                $app->make('App\Repositories\Server\ServerInterface')
            );
        });

        $this->app->bind('App\Services\Form\User\UserForm', function ($app) {
            return new UserForm(
                new UserFormLaravelValidator($app['validator']),
                $app->make('App\Repositories\User\UserInterface')
            );
        });

        $this->app->bind('App\Services\Notification\NotifierInterface', function ($app) {
            return new MailNotifier;
        });

        $this->app->bind('App\Console\Commands\Deploy', function ($app) {
            $processBuilder = new ProcessBuilder;

            return new Deploy(
                $app->make('App\Repositories\Project\ProjectInterface'),
                $app->make('App\Repositories\Deployment\DeploymentInterface'),
                $processBuilder
            );
        });

        $this->app->bind('App\Console\Commands\Rollback', function ($app) {
            $processBuilder = new ProcessBuilder;

            return new Rollback(
                $app->make('App\Repositories\Project\ProjectInterface'),
                $app->make('App\Repositories\Deployment\DeploymentInterface'),
                $processBuilder
            );
        });
    }
}
