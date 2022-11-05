<?php
declare(strict_types=1);

namespace App\Providers;

use App\Models\Project;
use App\Models\Recipe;
use App\Models\Server;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\Project\EloquentProject;
use App\Repositories\Recipe\EloquentRecipe;
use App\Repositories\Server\EloquentServer;
use App\Repositories\User\EloquentUser;
use App\Repositories\Role\EloquentRole;
use App\Repositories\Setting\ConfigAppSetting;
use App\Repositories\Setting\ConfigDbSetting;
use App\Repositories\Setting\EloquentSetting;

use Kodeine\Acl\Models\Eloquent\Role;

use Illuminate\Support\ServiceProvider;

/**
 * Class RepositoryServiceProvider
 * @package App\Providers
 */
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
            return new EloquentProject(new Project);
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

        $this->app->bind('App\Repositories\Setting\SettingInterface', function ($app) {
            return new EloquentSetting(new Setting);
        });

        $this->app->bind('App\Repositories\Setting\DbSettingInterface', function ($app) {
            return new ConfigDbSetting(
                $app->make('App\Services\Config\ConfigReaderInterface'),
                $app->make('App\Services\Config\ConfigWriterInterface')
            );
        });

        $this->app->bind('App\Repositories\Setting\AppSettingInterface', function ($app) {
            return new ConfigAppSetting(
                $app->make('App\Services\Config\ConfigReaderInterface'),
                $app->make('App\Services\Config\ConfigWriterInterface')
            );
        });
    }
}
