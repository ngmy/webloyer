<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Webloyer\Infra\Persistence\Eloquent\Models\{
    Deployment,
    Project,
};

class WebloyerRouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'Webloyer\Infra\Framework\Laravel\App\Http\Controllers';

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
            $projectOrm = Project::find($id);
            if (is_null($projectOrm)) {
                abort(404);
            }
            return $projectOrm->toEntity();
        });

        Route::bind('deployment', function ($number, $route) {
            $project = $route->parameter('project');
            $deploymentOrm = Deployment::ofId($project->id(), $number)->first();
            if (is_null($deploymentOrm)) {
                abort(404);
            }
            return $deploymentOrm->toEntity();
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
             ->group(__DIR__ . '/../../routes/web.php');
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
             ->group(__DIR__ . '/../../routes/api.php');
    }

    /**
     * @return void
     */
    protected function mapWebhookRoutes()
    {
        Route::prefix('webhook')
             ->middleware('api')
             ->namespace($this->namespace . '\Webhook')
             ->group(__DIR__ . '/../../routes/webhook.php');
    }
}
