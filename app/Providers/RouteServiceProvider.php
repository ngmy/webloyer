<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;
use Ngmy\Webloyer\Webloyer\Application\Server\ServerService;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        parent::boot($router);

        //
        $router->bind('project', function ($id) {
            $projectService = $this->app->make(ProjectService::class);

            $project = $projectService->getProjectById($id);

            if (is_null($project)) {
                throw new NotFoundHttpException();
            }

            return $project;
        });

        $router->bind('deployment', function ($id, $route) {
            $deploymentService = $this->app->make(DeploymentService::class);

            $project = $route->parameter('project');
            $deployment = $deploymentService->getDeploymentOfId($project->projectId()->id(), $id);

            if (is_null($deployment)) {
                throw new NotFoundHttpException();
            }

            return $deployment;
        });

        $router->bind('recipe', function ($id) {
            $recipeService = $this->app->make(RecipeService::class);

            $recipe = $recipeService->getRecipeOfId($id);

            if (is_null($recipe)) {
                throw new NotFoundHttpException();
            }

            return $recipe;
        });

        $router->bind('server', function ($id) {
            $serverService = $this->app->make(ServerService::class);

            $server = $serverService->getServerOfId($id);

            if (is_null($server)) {
                throw new NotFoundHttpException();
            }

            return $server;
        });

        $router->bind('user', function ($id) {
            $userService = $this->app->make(UserService::class);

            $useId = $id;
            $user = $userService->getUserOfId($useId);

            if (is_null($user)) {
                throw new NotFoundHttpException();
            }

            return $user;
        });
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
