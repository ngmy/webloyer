<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Deployment;
use App\Models\MaxDeployment;
use App\Models\Recipe;
use App\Models\Server;
use App\Models\User;
use App\Repositories\Project\EloquentProject;
use App\Repositories\Deployment\EloquentDeployment;
use App\Repositories\Recipe\EloquentRecipe;
use App\Repositories\Server\EloquentServer;
use App\Repositories\User\EloquentUser;
use App\Repositories\Role\EloquentRole;

use Kodeine\Acl\Models\Eloquent\Role;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\Project\ProjectInterface', function ($app) {
            return new EloquentProject(
                new Project,
                new MaxDeployment
            );
        });

        $this->app->bind('App\Repositories\Deployment\DeploymentInterface', function ($app) {
            return new EloquentDeployment(new Deployment);
        });

        $this->app->bind('App\Repositories\Recipe\RecipeInterface', function ($app) {
            return new EloquentRecipe(new Recipe);
        });

        $this->app->bind('App\Repositories\Server\ServerInterface', function ($app) {
            return new EloquentServer(new Server);
        });

        $this->app->bind('App\Repositories\User\UserInterface', function ($app) {
            return new EloquentUser(new User);
        });

        $this->app->bind('App\Repositories\Role\RoleInterface', function ($app) {
            return new EloquentRole(new Role);
        });
    }
}
