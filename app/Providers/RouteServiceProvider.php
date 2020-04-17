<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Webloyer\Infra\Db\Eloquents;

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
    public const HOME = '/projects';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        Route::bind('project', function ($id) {
            $projectRepository = $this->app->make('App\Repositories\Project\ProjectInterface');

            $project = $projectRepository->byId($id);

            if (is_null($project)) {
                abort(404);
            }

            return $project;
        });

        Route::bind('deployment', function ($num, $route) {
            $project = $route->parameter('project');

            $deployment = $project->getDeploymentByNumber($num);

            if (is_null($deployment)) {
                abort(404);
            }

            return $deployment;
        });

        Route::bind('recipe', function (int $id) {
            $recipeOrm = Eloquents\Recipe\Recipe::find($id);
            if (is_null($recipeOrm)) {
                abort(404);
            }
            return $recipeOrm->toEntity();
        });

        Route::bind('server', function ($id) {
            $serverRepository = $this->app->make('App\Repositories\Server\ServerInterface');

            $server = $serverRepository->byId($id);

            if (is_null($server)) {
                abort(404);
            }

            return $server;
        });

        Route::bind('user', function ($id) {
            $userRepository = $this->app->make('App\Repositories\User\UserInterface');

            $user = $userRepository->byId($id);

            if (is_null($user)) {
                abort(404);
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
             ->namespace($this->namespace . '\Api')
             ->group(base_path('routes/api.php'));
    }

    /**
     * @return void
     */
    protected function mapWebhookRoutes()
    {
        Route::prefix('webhook')
             ->middleware('api')
             ->namespace($this->namespace . '\Webhook')
             ->group(base_path('routes/webhook.php'));
    }
}
