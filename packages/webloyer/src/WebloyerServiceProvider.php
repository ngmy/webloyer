<?php

namespace Ngmy\Webloyer\Webloyer;

use Illuminate\Support\ServiceProvider;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Dumper;
use Ngmy\Webloyer\Common\Filesystem\FilesystemInterface;
use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerDeploymentFileBuilder;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerRecipeFileBuilder;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerServerListFileBuilder;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\AppSettingRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\DbSettingRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSettingRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Service\Deployer\DeployerDispatcherServiceInterface;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\DotenvAppSettingRepository;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\DotenvDbSettingRepository;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\EloquentProjectRepository;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\EloquentDeploymentRepository;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\EloquentMailSettingRepository;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\EloquentRecipeRepository;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\EloquentServerRepository;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Messaging\QueueDeployerDispatcherService;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ProjectForm\ProjectForm;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ProjectForm\ProjectFormLaravelValidator;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm\DeploymentForm;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm\DeploymentFormLaravelValidator;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm\RecipeForm;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\RecipeForm\RecipeFormLaravelValidator;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ServerForm\ServerForm;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\ServerForm\ServerFormLaravelValidator;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\SettingForm\MailSettingForm;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\SettingForm\MailSettingFormLaravelValidator;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\UserForm\UserForm;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\UserForm\UserFormLaravelValidator;

class WebloyerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProjectRepositoryInterface::class, EloquentProjectRepository::class);
        $this->app->bind(DeploymentRepositoryInterface::class, EloquentDeploymentRepository::class);
        $this->app->bind(ServerRepositoryInterface::class, EloquentServerRepository::class);
        $this->app->bind(RecipeRepositoryInterface::class, EloquentRecipeRepository::class);
        $this->app->bind(MailSettingRepositoryInterface::class, EloquentMailSettingRepository::class);
        $this->app->bind(AppSettingRepositoryInterface::class, DotenvAppSettingRepository::class);
        $this->app->bind(DbSettingRepositoryInterface::class, DotenvDbSettingRepository::class);

        $this->app->bind(DeployerDispatcherServiceInterface::class, QueueDeployerDispatcherService::class);
        $this->app->bind(DeployerServerListFileBuilder::class, function ($app) {
            return new DeployerServerListFileBuilder(
                $app->make(FilesystemInterface::class),
                new DeployerFile(),
                new Parser(),
                new Dumper()
            );
        });
        $this->app->bind(DeployerRecipeFileBuilder::class, function ($app) {
            return new DeployerRecipeFileBuilder(
                $app->make(FilesystemInterface::class),
                new DeployerFile()
            );
        });
        $this->app->bind(DeployerDeploymentFileBuilder::class, function ($app) {
            return new DeployerDeploymentFileBuilder(
                $app->make(FilesystemInterface::class),
                new DeployerFile()
            );
        });

        $this->app->when(ProjectForm::class)
            ->needs(ValidableInterface::class)
            ->give(function ($app) {
                return new ProjectFormLaravelValidator($app['validator']);
            });
        $this->app->when(DeploymentForm::class)
            ->needs(ValidableInterface::class)
            ->give(function ($app) {
                return new DeploymentFormLaravelValidator($app['validator']);
            });
        $this->app->when(RecipeForm::class)
            ->needs(ValidableInterface::class)
            ->give(function ($app) {
                return new RecipeFormLaravelValidator($app['validator']);
            });
        $this->app->when(ServerForm::class)
            ->needs(ValidableInterface::class)
            ->give(function ($app) {
                return new ServerFormLaravelValidator($app['validator']);
            });
        $this->app->when(MailSettingForm::class)
            ->needs(ValidableInterface::class)
            ->give(function ($app) {
                return new MailSettingFormLaravelValidator($app['validator']);
            });
        $this->app->when(UserForm::class)
            ->needs(ValidableInterface::class)
            ->give(function ($app) {
                return new UserFormLaravelValidator($app['validator']);
            });
    }
}
