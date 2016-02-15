<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $router->bind('projects', function ($id) {
            $projectRepository = $this->app->make('App\Repositories\Project\ProjectInterface');

            $project = $projectRepository->byId($id);

            if (is_null($project)) {
                throw new NotFoundHttpException;
            }

            return $project;
        });

        $router->bind('deployments', function ($num, $route) {
            $project = $route->parameter('projects');

            $deployment = $project->getDeploymentByNumber($num);

            if (is_null($deployment)) {
                throw new NotFoundHttpException;
            }

            return $deployment;
        });

        $router->bind('recipes', function ($id) {
            $recipeRepository = $this->app->make('App\Repositories\Recipe\RecipeInterface');

            $recipe = $recipeRepository->byId($id);

            if (is_null($recipe)) {
                throw new NotFoundHttpException;
            }

            return $recipe;
        });

        $router->bind('servers', function ($id) {
            $serverRepository = $this->app->make('App\Repositories\Server\ServerInterface');

            $server = $serverRepository->byId($id);

            if (is_null($server)) {
                throw new NotFoundHttpException;
            }

            return $server;
        });

        $router->bind('users', function ($id) {
            $userRepository = $this->app->make('App\Repositories\User\UserInterface');

            $user = $userRepository->byId($id);

            if (is_null($user)) {
                throw new NotFoundHttpException;
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
