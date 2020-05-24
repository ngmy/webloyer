<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Providers;

use Common\App\Service\{
    ApplicationService,
    TransactionalApplicationService,
    TransactionalSession,
};
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Webloyer\App\DataTransformer\Recipe\{
    RecipeDataTransformer,
    RecipeDtoDataTransformer,
    RecipesDataTransformer,
    RecipesDtoDataTransformer,
};
use Webloyer\App\DataTransformer\Server\{
    ServerDataTransformer,
    ServerDtoDataTransformer,
    ServersDataTransformer,
    ServersDtoDataTransformer,
};
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
use Webloyer\App\Service\Server\{
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
use Webloyer\Infra\App\DataTransformer\Recipe\RecipesLaravelLengthAwarePaginatorDataTransformer;

class WebloyerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // server data transformers
        $this->app->bind(ServerDataTransformer::class, ServerDtoDataTransformer::class);
        $this->app->bind(ServersDataTransformer::class, ServersDtoDataTransformer::class);

        // recipe data transformers
        $this->app->bind(RecipeDataTransformer::class, RecipeDtoDataTransformer::class);
        $this->app->bind(RecipesDataTransformer::class, RecipesLaravelLengthAwarePaginatorDataTransformer::class);

        // deployment app services
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

        // project app services
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

        // recipe app services
        $this->app->bind(CreateRecipeService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new CreateRecipeService(
                    $app->make(RecipeRepository::class),
                    $app->make(RecipeDataTransformer::class),
                    $app->make(RecipesDataTransformer::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(DeleteRecipeService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new DeleteRecipeService(
                    $app->make(RecipeRepository::class),
                    $app->make(RecipeDataTransformer::class),
                    $app->make(RecipesDataTransformer::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(UpdateRecipeService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new UpdateRecipeService(
                    $app->make(RecipeRepository::class),
                    $app->make(RecipeDataTransformer::class),
                    $app->make(RecipesDataTransformer::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });

        // server app services
        $this->app->bind(CreateServerService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new CreateServerService(
                    $app->make(ServerRepository::class),
                    $app->make(ServerDataTransformer::class),
                    $app->make(ServersDataTransformer::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(DeleteServerService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new DeleteServerService(
                    $app->make(ServerRepository::class),
                    $app->make(ServerDataTransformer::class),
                    $app->make(ServersDataTransformer::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->bind(UpdateServerService::class, function (Application $app): ApplicationService {
            return new TransactionalApplicationService(
                new UpdateServerService(
                    $app->make(ServerRepository::class),
                    $app->make(ServerDataTransformer::class),
                    $app->make(ServersDataTransformer::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });

        // repositories
        $this->app->bind(DeploymentRepository::class, EloquentDeploymentRepository::class);
        $this->app->bind(ProjectRepository::class, EloquentProjectRepository::class);
        $this->app->bind(RecipeRepository::class, EloquentRecipeRepository::class);
        $this->app->bind(ServerRepository::class, EloquentServerRepository::class);
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);

        // other service providers
        $this->app->register(WebloyerAssetServiceProvider::class);
        $this->app->register(WebloyerCommandServiceProvider::class);
        $this->app->register(WebloyerDatabaseServiceProvider::class);
        $this->app->register(WebloyerEventServiceProvider::class);
        $this->app->register(WebloyerMiddlewareServiceProvider::class);
        $this->app->register(WebloyerRouteServiceProvider::class);
        $this->app->register(WebloyerTranslationServiceProvider::class);
        $this->app->register(WebloyerViewServiceProvider::class);
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
