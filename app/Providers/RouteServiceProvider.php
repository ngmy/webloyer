<?php
declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class RouteServiceProvider
 * @package App\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        Route::bind('projects', function ($id) {
            $projectRepository = $this->app->make('App\Repositories\Project\ProjectInterface');
            $project = $projectRepository->byId($id);
            if (is_null($project)) {
                throw new NotFoundHttpException;
            }
            return $project;
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

        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));

    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
