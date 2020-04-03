<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
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
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::bind('projects', function ($id) {
            $projectRepository = $this->app->make('App\Repositories\Project\ProjectInterface');

            $project = $projectRepository->byId($id);

            if (is_null($project)) {
                throw new NotFoundHttpException;
            }

            return $project;
        });

        Route::bind('deployments', function ($num, $route) {
            $project = $route->parameter('projects');

            $deployment = $project->getDeploymentByNumber($num);

            if (is_null($deployment)) {
                throw new NotFoundHttpException;
            }

            return $deployment;
        });

        Route::bind('recipes', function ($id) {
            $recipeRepository = $this->app->make('App\Repositories\Recipe\RecipeInterface');

            $recipe = $recipeRepository->byId($id);

            if (is_null($recipe)) {
                throw new NotFoundHttpException;
            }

            return $recipe;
        });

        Route::bind('servers', function ($id) {
            $serverRepository = $this->app->make('App\Repositories\Server\ServerInterface');

            $server = $serverRepository->byId($id);

            if (is_null($server)) {
                throw new NotFoundHttpException;
            }

            return $server;
        });

        Route::bind('users', function ($id) {
            $userRepository = $this->app->make('App\Repositories\User\UserInterface');

            $user = $userRepository->byId($id);

            if (is_null($user)) {
                throw new NotFoundHttpException;
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
}
