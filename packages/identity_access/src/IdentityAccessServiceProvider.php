<?php

namespace Ngmy\Webloyer\IdentityAccess;

use Illuminate\Support\ServiceProvider;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\Role\RoleRepositoryInterface;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserRepositoryInterface;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\EloquentRoleRepository;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\EloquentUserProvider;
use Ngmy\Webloyer\IdentityAccess\Port\Adapter\Persistence\EloquentUserRepository;

class IdentityAccessServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['auth']->provider('eloquent', function ($app) {
            return new EloquentUserProvider(
                $app['hash'],
                $app['config']['auth.providers.users.model'],
                $app->make(UserRepositoryInterface::class)
            );
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, EloquentRoleRepository::class);
    }
}
