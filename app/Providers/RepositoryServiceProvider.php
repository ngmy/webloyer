<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\Project\EloquentProject;
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
            return new EloquentProject(new Project());
        });

        $this->app->bind('App\Repositories\User\UserInterface', function ($app) {
            return new EloquentUser(new User());
        });

        $this->app->bind('App\Repositories\Role\RoleInterface', function ($app) {
            return new EloquentRole(new Role());
        });
    }
}
