<?php

declare(strict_types=1);

namespace Deployer\Infra\Framework\Laravel\App\Providers;

use Illuminate\Support\ServiceProvider;

class DeployerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        // other service providers
        $this->app->register(DeployerEventServiceProvider::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
