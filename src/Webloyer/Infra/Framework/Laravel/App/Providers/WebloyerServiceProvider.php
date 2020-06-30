<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Providers;

use Common\App\Service\{
    ApplicationService,
    TransactionalApplicationService,
    TransactionalSession,
};
use Common\ServiceBus\QueryBus;
use Datto\JsonRpc\Server as JsonRpcServer;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Webloyer\App\DataTransformer\Deployment\{
    DeploymentDataTransformer,
    DeploymentDtoDataTransformer,
    DeploymentsDataTransformer,
    DeploymentsDtoDataTransformer,
};
use Webloyer\App\DataTransformer\Project\{
    ProjectDataTransformer,
    ProjectDtoDataTransformer,
    ProjectsDataTransformer,
    ProjectsDtoDataTransformer,
};
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
use Webloyer\App\DataTransformer\User\{
    UserDataTransformer,
    UserDtoDataTransformer,
    UsersDataTransformer,
    UsersDtoDataTransformer,
};
use Webloyer\App\Service\Deployment\{
    CreateDeploymentService,
    DeleteDeploymentService,
    FinishDeploymentService,
    GetDeploymentService,
    GetDeploymentsService,
    ProgressDeploymentService,
    RollbackDeploymentService,
    StartDeploymentService,
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
use Webloyer\App\Service\User\{
    CreateUserService,
    DeleteUserService,
    GetAllUsersService,
    GetUserService,
    GetUsersService,
    UpdateUserService,
    UpdatePasswordService,
    UpdateRoleService,
    RegenerateApiTokenService,
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
use Webloyer\Infra\App\DataTransformer\Deployment\DeploymentsLaravelLengthAwarePaginatorDataTransformer;
use Webloyer\Infra\App\DataTransformer\Project\ProjectsLaravelLengthAwarePaginatorDataTransformer;
use Webloyer\Infra\App\DataTransformer\Recipe\RecipesLaravelLengthAwarePaginatorDataTransformer;
use Webloyer\Infra\App\DataTransformer\Server\ServersLaravelLengthAwarePaginatorDataTransformer;
use Webloyer\Infra\App\DataTransformer\User\UsersLaravelLengthAwarePaginatorDataTransformer;
use Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Api\V1\JsonRpc\JsonRpcController;
use Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Deployment\{
    DeployController as DeploymentDeployController,
    IndexController as DeploymentIndexController,
    RollbackController as DeploymentRollbackController,
    ShowController as DeploymentShowController,
};
use Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Project\{
    CreateController as ProjectCreateController,
    DestroyController as ProjectDestroyController,
    EditController as ProjectEditController,
    IndexController as ProjectIndexController,
    ShowController as ProjectShowController,
    StoreController as ProjectStoreController,
    UpdateController as ProjectUpdateController,
};
use Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Recipe\{
    DestroyController as RecipeDestroyController,
    EditController as RecipeEditController,
    IndexController as RecipeIndexController,
    ShowController as RecipeShowController,
    StoreController as RecipeStoreController,
    UpdateController as RecipeUpdateController,
};
use Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Server\{
    DestroyController as ServerDestroyController,
    EditController as ServerEditController,
    IndexController as ServerIndexController,
    ShowController as ServerShowController,
    StoreController as ServerStoreController,
    UpdateController as ServerUpdateController,
};
use Webloyer\Infra\Framework\Laravel\App\Http\Controllers\User\{
    ChangePasswordController as UserChangePasswordController,
    DestroyController as UserDestroyController,
    EditController as UserEditController,
    EditApiTokenController as UserEditApiTokenController,
    EditRoleController as UserEditRoleController,
    IndexController as UserIndexController,
    ShowController as UserShowController,
    StoreController as UserStoreController,
    RegenerateApiTokenController as UserRegenerateApiTokenController,
    UpdateController as UserUpdateController,
    UpdatePasswordController as UserUpdatePassowordController,
    UpdateRoleController as UserUpdateRoleController,
};
use Webloyer\Infra\Framework\Laravel\App\Http\Controllers\Webhook\V1\GitHub\Deployment\{
    DeployController as WebhookV1GitHubDeploymentDeployController,
    RollbackController as WebhookV1GitHubDeploymentRollbackController,
};
use Webloyer\Infra\Framework\Laravel\App\Listeners\{
    DeployerFinishedListener,
    DeployerProgressedListener,
    DeployerStartedListener,
};
use Webloyer\Infra\Ui\Api\JsonRpc\Api as JsonRpcApi;
use Webloyer\Query\AllRolesQueryHandler;

class WebloyerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // deployment data transformers
        $this->app->bind(DeploymentDataTransformer::class, DeploymentDtoDataTransformer::class);
        $this->app->bind(DeploymentsDataTransformer::class, DeploymentsLaravelLengthAwarePaginatorDataTransformer::class);

        // project data transformers
        $this->app->bind(ProjectDataTransformer::class, ProjectDtoDataTransformer::class);
        $this->app->bind(ProjectsDataTransformer::class, ProjectsLaravelLengthAwarePaginatorDataTransformer::class);

        // server data transformers
        $this->app->bind(ServerDataTransformer::class, ServerDtoDataTransformer::class);
        $this->app->bind(ServersDataTransformer::class, ServersLaravelLengthAwarePaginatorDataTransformer::class);

        // recipe data transformers
        $this->app->bind(RecipeDataTransformer::class, RecipeDtoDataTransformer::class);
        $this->app->bind(RecipesDataTransformer::class, RecipesLaravelLengthAwarePaginatorDataTransformer::class);

        // user data transformers
        $this->app->bind(UserDataTransformer::class, UserDtoDataTransformer::class);
        $this->app->bind(UsersDataTransformer::class, UsersLaravelLengthAwarePaginatorDataTransformer::class);

        $this->app->when(JsonRpcController::class)
            ->needs(JsonRpcServer::class)
            ->give(function (Application $app): JsonRpcServer {
                return new JsonRpcServer($app->make(JsonRpcApi::class));
            });

        // deployment app services
        $this->app->when(DeploymentIndexController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetDeploymentsService(
                    $app->make(DeploymentRepository::class),
                    $app->make(ProjectRepository::class),
                    $app->make(RecipeRepository::class),
                    $app->make(ServerRepository::class),
                    $app->make(UserRepository::class),
                    $app->make(DeploymentDataTransformer::class),
                    $app->make(DeploymentsDataTransformer::class)
                );
            });
        $this->app->when(DeploymentDeployController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new CreateDeploymentService(
                        $app->make(DeploymentRepository::class),
                        $app->make(ProjectRepository::class),
                        $app->make(RecipeRepository::class),
                        $app->make(ServerRepository::class),
                        $app->make(UserRepository::class),
                        $app->make(DeploymentDataTransformer::class),
                        $app->make(DeploymentsDataTransformer::class)
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
                    $app->make(DeploymentDataTransformer::class),
                    $app->make(DeploymentsDataTransformer::class)
                ),
                $app->make(TransactionalSession::class)
            );
        });
        $this->app->when(DeploymentRollbackController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new RollbackDeploymentService(
                        $app->make(DeploymentRepository::class),
                        $app->make(ProjectRepository::class),
                        $app->make(RecipeRepository::class),
                        $app->make(ServerRepository::class),
                        $app->make(UserRepository::class),
                        $app->make(DeploymentDataTransformer::class),
                        $app->make(DeploymentsDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(DeploymentShowController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetDeploymentService(
                    $app->make(DeploymentRepository::class),
                    $app->make(ProjectRepository::class),
                    $app->make(RecipeRepository::class),
                    $app->make(ServerRepository::class),
                    $app->make(UserRepository::class),
                    $app->make(DeploymentDataTransformer::class),
                    $app->make(DeploymentsDataTransformer::class)
                );
            });
        $this->app->when(WebhookV1GitHubDeploymentDeployController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new CreateDeploymentService(
                        $app->make(DeploymentRepository::class),
                        $app->make(ProjectRepository::class),
                        $app->make(RecipeRepository::class),
                        $app->make(ServerRepository::class),
                        $app->make(UserRepository::class),
                        $app->make(DeploymentDataTransformer::class),
                        $app->make(DeploymentsDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(WebhookV1GitHubDeploymentRollbackController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new RollbackDeploymentService(
                        $app->make(DeploymentRepository::class),
                        $app->make(ProjectRepository::class),
                        $app->make(RecipeRepository::class),
                        $app->make(ServerRepository::class),
                        $app->make(UserRepository::class),
                        $app->make(DeploymentDataTransformer::class),
                        $app->make(DeploymentsDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(DeployerFinishedListener::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new FinishDeploymentService(
                        $app->make(DeploymentRepository::class),
                        $app->make(ProjectRepository::class),
                        $app->make(RecipeRepository::class),
                        $app->make(ServerRepository::class),
                        $app->make(UserRepository::class),
                        $app->make(DeploymentDataTransformer::class),
                        $app->make(DeploymentsDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(DeployerProgressedListener::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new ProgressDeploymentService(
                        $app->make(DeploymentRepository::class),
                        $app->make(ProjectRepository::class),
                        $app->make(RecipeRepository::class),
                        $app->make(ServerRepository::class),
                        $app->make(UserRepository::class),
                        $app->make(DeploymentDataTransformer::class),
                        $app->make(DeploymentsDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(DeployerStartedListener::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new StartDeploymentService(
                        $app->make(DeploymentRepository::class),
                        $app->make(ProjectRepository::class),
                        $app->make(RecipeRepository::class),
                        $app->make(ServerRepository::class),
                        $app->make(UserRepository::class),
                        $app->make(DeploymentDataTransformer::class),
                        $app->make(DeploymentsDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });

        // project app services
        $this->app->when([ProjectEditController::class, ProjectShowController::class])
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetProjectService(
                    $app->make(ProjectRepository::class),
                    $app->make(ProjectDataTransformer::class),
                    $app->make(ProjectsDataTransformer::class)
                );
            });
        $this->app->when(ProjectDestroyController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new DeleteProjectService(
                        $app->make(ProjectRepository::class),
                        $app->make(ProjectDataTransformer::class),
                        $app->make(ProjectsDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(ProjectIndexController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetProjectsService(
                    $app->make(ProjectRepository::class),
                    $app->make(ProjectDataTransformer::class),
                    $app->make(ProjectsDataTransformer::class)
                );
            });
        $this->app->when(ProjectStoreController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new CreateProjectService(
                        $app->make(ProjectRepository::class),
                        $app->make(ProjectDataTransformer::class),
                        $app->make(ProjectsDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(ProjectUpdateController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new UpdateProjectService(
                        $app->make(ProjectRepository::class),
                        $app->make(ProjectDataTransformer::class),
                        $app->make(ProjectsDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });

        // recipe app services
        $this->app->when([ProjectCreateController::class, ProjectEditController::class])
            ->needs(GetRecipesService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetRecipesService(
                    $app->make(RecipeRepository::class),
                    $app->make(RecipeDataTransformer::class),
                    $app->make(RecipesDtoDataTransformer::class)
                );
            });
        $this->app->when([RecipeEditController::class, RecipeShowController::class])
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetRecipeService(
                    $app->make(RecipeRepository::class),
                    $app->make(RecipeDataTransformer::class),
                    $app->make(RecipesDataTransformer::class)
                );
            });
        $this->app->when(RecipeDestroyController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new DeleteRecipeService(
                        $app->make(RecipeRepository::class),
                        $app->make(RecipeDataTransformer::class),
                        $app->make(RecipesDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(RecipeIndexController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetRecipesService(
                    $app->make(RecipeRepository::class),
                    $app->make(RecipeDataTransformer::class),
                    $app->make(RecipesDataTransformer::class)
                );
            });
        $this->app->when(RecipeStoreController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new CreateRecipeService(
                        $app->make(RecipeRepository::class),
                        $app->make(RecipeDataTransformer::class),
                        $app->make(RecipesDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(RecipeUpdateController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
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
        $this->app->when([ProjectCreateController::class, ProjectEditController::class])
            ->needs(GetServersService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetServersService(
                    $app->make(ServerRepository::class),
                    $app->make(ServerDataTransformer::class),
                    $app->make(ServersDtoDataTransformer::class)
                );
            });
        $this->app->when([ServerEditController::class, ServerShowController::class])
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetServerService(
                    $app->make(ServerRepository::class),
                    $app->make(ServerDataTransformer::class),
                    $app->make(ServersDataTransformer::class)
                );
            });
        $this->app->when(ServerDestroyController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new DeleteServerService(
                        $app->make(ServerRepository::class),
                        $app->make(ServerDataTransformer::class),
                        $app->make(ServersDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(ServerIndexController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetServersService(
                    $app->make(ServerRepository::class),
                    $app->make(ServerDataTransformer::class),
                    $app->make(ServersDataTransformer::class)
                );
            });
        $this->app->when(ServerStoreController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new CreateServerService(
                        $app->make(ServerRepository::class),
                        $app->make(ServerDataTransformer::class),
                        $app->make(ServersDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(ServerUpdateController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new UpdateServerService(
                        $app->make(ServerRepository::class),
                        $app->make(ServerDataTransformer::class),
                        $app->make(ServersDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });

        // user app services
        $this->app->when([ProjectCreateController::class, ProjectEditController::class])
            ->needs(GetUsersService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetUsersService(
                    $app->make(UserRepository::class),
                    $app->make(UserDataTransformer::class),
                    $app->make(UsersDtoDataTransformer::class)
                );
            });
        $this->app->when([
            UserChangePasswordController::class,
            UserEditController::class,
            UserEditApiTokenController::class,
            UserEditRoleController::class,
            UserShowController::class,
        ])
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetUserService(
                    $app->make(UserRepository::class),
                    $app->make(UserDataTransformer::class),
                    $app->make(UsersDataTransformer::class)
                );
            });
        $this->app->when(UserDestroyController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new DeleteUserService(
                        $app->make(UserRepository::class),
                        $app->make(UserDataTransformer::class),
                        $app->make(UsersDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(UserIndexController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new GetUsersService(
                    $app->make(UserRepository::class),
                    $app->make(UserDataTransformer::class),
                    $app->make(UsersDataTransformer::class)
                );
            });
        $this->app->when(UserStoreController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new CreateUserService(
                        $app->make(UserRepository::class),
                        $app->make(UserDataTransformer::class),
                        $app->make(UsersDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(UserUpdateController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new UpdateUserService(
                        $app->make(UserRepository::class),
                        $app->make(UserDataTransformer::class),
                        $app->make(UsersDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(UserUpdatePassowordController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new UpdatePasswordService(
                        $app->make(UserRepository::class),
                        $app->make(UserDataTransformer::class),
                        $app->make(UsersDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(UserUpdateRoleController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new UpdateRoleService(
                        $app->make(UserRepository::class),
                        $app->make(UserDataTransformer::class),
                        $app->make(UsersDataTransformer::class)
                    ),
                    $app->make(TransactionalSession::class)
                );
            });
        $this->app->when(UserRegenerateApiTokenController::class)
            ->needs(ApplicationService::class)
            ->give(function (Application $app): ApplicationService {
                return new TransactionalApplicationService(
                    new RegenerateApiTokenService(
                        $app->make(UserRepository::class),
                        $app->make(UserDataTransformer::class),
                        $app->make(UsersDataTransformer::class)
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

        // service bus
        $this->app->singleton(QueryBus::class, function (Application $app): QueryBus {
            $queryBus = new QueryBus();
            $queryBus->register(new AllRolesQueryHandler());
            return $queryBus;
        });

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
