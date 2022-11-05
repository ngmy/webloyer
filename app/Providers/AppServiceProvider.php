<?php
declare(strict_types=1);

namespace App\Providers;

use App\Services\Api\JsonRpc;
use App\Services\Config\DotenvReader;
use App\Services\Config\DotenvWriter;
use App\Services\Deployment\DeployerDeploymentFileBuilder;
use App\Services\Deployment\DeployerFile;
use App\Services\Deployment\DeployerRecipeFileBuilder;
use App\Services\Deployment\DeployerServerListFileBuilder;
use App\Services\Deployment\QueueDeployCommander;
use App\Services\Filesystem\LaravelFilesystem;
use App\Services\Form\Deployment\DeploymentForm;
use App\Services\Form\Deployment\DeploymentFormLaravelValidator;
use App\Services\Form\Project\ProjectForm;
use App\Services\Form\Project\ProjectFormLaravelValidator;
use App\Services\Form\Recipe\RecipeForm;
use App\Services\Form\Recipe\RecipeFormLaravelValidator;
use App\Services\Form\Server\ServerForm;
use App\Services\Form\Server\ServerFormLaravelValidator;
use App\Services\Form\Setting\MailSettingForm;
use App\Services\Form\Setting\MailSettingFormLaravelValidator;
use App\Services\Form\User\UserForm;
use App\Services\Form\User\UserFormLaravelValidator;
use App\Services\Notification\MailNotifier;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Dumper;
use Symfony\Component\Yaml\Parser;

/**
 * Class AppServiceProvider
 * @package App\Providers
 */
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
                $app->make('App\Repositories\Project\ProjectInterface'),
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

        $this->app->bind('App\Services\Form\Setting\MailSettingForm', function ($app) {
            return new MailSettingForm(
                new MailSettingFormLaravelValidator($app['validator']),
                $app->make('App\Repositories\Setting\SettingInterface')
            );
        });

        $this->app->bind('App\Services\Notification\NotifierInterface', function ($app) {
            return new MailNotifier;
        });

        $this->app->bind('App\Services\Config\ConfigReaderInterface', function ($app) {
            $path = base_path('.env');

            return new DotenvReader(
                new LaravelFilesystem($app['files']),
                $path
            );
        });

        $this->app->bind('App\Services\Config\ConfigWriterInterface', function ($app) {
            $path = base_path('.env');

            return new DotenvWriter(
                new LaravelFilesystem($app['files']),
                $path
            );
        });

        $this->app->bind('App\Services\Deployment\DeployerServerListFileBuilder', function ($app) {
            return new DeployerServerListFileBuilder(
                new LaravelFilesystem($app['files']),
                new DeployerFile,
                new Parser,
                new Dumper
            );
        });
        $this->app->bind('App\Services\Deployment\DeployerRecipeFileBuilder', function ($app) {
            return new DeployerRecipeFileBuilder(
                new LaravelFilesystem($app['files']),
                new DeployerFile
            );
        });
        $this->app->bind('App\Services\Deployment\DeployerDeploymentFileBuilder', function ($app) {
            return new DeployerDeploymentFileBuilder(
                new LaravelFilesystem($app['files']),
                new DeployerFile
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
