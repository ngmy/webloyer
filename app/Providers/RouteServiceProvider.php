<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;
use Ngmy\Webloyer\Webloyer\Application\Server\ServerService;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        Route::bind('project', function ($id) {
            $projectService = $this->app->make(ProjectService::class);

            $project = $projectService->getProjectById($id);

            if (is_null($project)) {
                throw new NotFoundHttpException();
            }

            return $project;
        });

        Route::bind('deployment', function ($id, $route) {
            $deploymentService = $this->app->make(DeploymentService::class);

            $project = $route->parameter('project');
            $deployment = $deploymentService->getDeploymentById($project->projectId()->id(), $id);

            if (is_null($deployment)) {
                throw new NotFoundHttpException();
            }

            return $deployment;
        });

        Route::bind('recipe', function ($id) {
            $recipeService = $this->app->make(RecipeService::class);

            $recipe = $recipeService->getRecipeById($id);

            if (is_null($recipe)) {
                throw new NotFoundHttpException();
            }

            return $recipe;
        });

        Route::bind('server', function ($id) {
            $serverService = $this->app->make(ServerService::class);

            $server = $serverService->getServerById($id);

            if (is_null($server)) {
                throw new NotFoundHttpException();
            }

            return $server;
        });

        Route::bind('user', function ($id) {
            $userService = $this->app->make(UserService::class);

            $useId = $id;
            $user = $userService->getUserById($useId);

            if (is_null($user)) {
                throw new NotFoundHttpException();
            }

            return $user;
        });

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
        $this->mapWebhookRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    protected function mapWebhookRoutes()
    {
        Route::prefix('webhook')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/webhook.php'));
    }
}
