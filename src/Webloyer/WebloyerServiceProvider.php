<?php

declare(strict_types=1);

namespace Webloyer;

use Common\App\Service\{
    ApplicationService,
    TransactionalApplicationService,
    TransactionalSession,
};
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Webloyer\App\Service\Deployment\{
    CreateDeploymentService,
    DeleteDeploymentService,
    GetDeploymentService,
    GetDeploymentsService,
    RollbackDeploymentService,
};
use Webloyer\App\Service\Project\{
    CreateProjectService,
    DeleteProjectService,
    GetAllProjectsService,
    GetProjectService,
    GetProjectsService,
    UpdateProjectService,
};
use Webloyer\App\Service\Recipe\{
    CreateRecipeService,
    DeleteRecipeService,
    GetAllRecipesService,
    GetRecipeService,
    GetRecipesService,
    UpdateRecipeService,
};
use Webloyer\App\Service\Service\{
    CreateServerService,
    DeleteServerService,
    GetAllServersService,
    GetServerService,
    GetServersService,
    UpdateServerService,
};
use Webloyer\Domain\Model\Deployment\DeploymentRepository;
use Webloyer\Domain\Model\Project\ProjectRepository;
use Webloyer\Domain\Model\Recipe\RecipeRepository;
use Webloyer\Domain\Model\Server\ServerRepository;
use Webloyer\Domain\Model\User\UserRepository;
use Webloyer\Infra\Domain\Model\Deployment\EloquentDeploymentRepository;
use Webloyer\Infra\Domain\Model\Project\EloquentProjectRepository;
use Webloyer\Infra\Domain\Model\Recipe\EloquentRecipeRepository;
use Webloyer\Infra\Domain\Model\Server\EloquentServerRepository;
use Webloyer\Infra\Domain\Model\User\EloquentUserRepository;

class WebloyerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(CreateDeploymentService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new CreateDeploymentService(
                    $app->make(DeploymentRepository::class),
                    $app->make(ProjectRepository::class),
                    $app->make(RecipeRepository::class),
                    $app->make(ServerRepository::class),
                    $app->make(UserRepository::class),
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(DeleteDeploymentService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new DeleteDeploymentService(
                    $app->make(DeploymentRepository::class),
                    $app->make(ProjectRepository::class),
                    $app->make(RecipeRepository::class),
                    $app->make(ServerRepository::class),
                    $app->make(UserRepository::class),
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(RollbackDeploymentService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new RollbackDeploymentService(
                    $app->make(DeploymentRepository::class),
                    $app->make(ProjectRepository::class),
                    $app->make(RecipeRepository::class),
                    $app->make(ServerRepository::class),
                    $app->make(UserRepository::class),
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(CreateProjectService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new CreateProjectService(
                    $app->make(ProjectRepository::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(DeleteProjectService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new DeleteProjectService(
                    $app->make(ProjectRepository::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(UpdateProjectService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new UpdateProjectService(
                    $app->make(ProjectRepository::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(CreateRecipeService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new CreateRecipeService(
                    $app->make(RecipeRepository::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(DeleteRecipeService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new DeleteRecipeService(
                    $app->make(RecipeRepository::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(UpdateRecipeService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new UpdateRecipeService(
                    $app->make(RecipeRepository::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(CreateServerService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new CreateServerService(
                    $app->make(ServerRepository::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(DeleteServerService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new DeleteServerService(
                    $app->make(ServerRepository::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(UpdateServerService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new UpdateServerService(
                    $app->make(ServerRepository::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });

        $this->app->bind(DeploymentRepository::class, EloquentDeploymentRepository::class);
        $this->app->bind(ProjectRepository::class, EloquentProjectRepository::class);
        $this->app->bind(RecipeRepository::class, EloquentRecipeRepository::class);
        $this->app->bind(ServerRepository::class, EloquentServerRepository::class);
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);

        $this->app->register(WebloyerEventServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
