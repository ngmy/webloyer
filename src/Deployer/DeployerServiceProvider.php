<?php

declare(strict_types=1);

namespace Webloyer;

use Deployer\Infra\Messaging\LaravelQueue\LaravelQueueDeploymentRequestedListener;
use Event;
use Illuminate\Support\ServiceProvider;
use Webloyer\Domain\Model\Deployment\DeploymentRequested;

class WebloyerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        Event::listen(DeploymentRequested::class, LaravelQueueDeploymentRequestedListener::class);
    }
}
