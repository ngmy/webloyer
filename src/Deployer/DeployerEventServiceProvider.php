<?php

declare(strict_types=1);

namespace Deployer;

use Deployer\Infra\Messaging\Laravel\LaravelDeploymentRequestedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;
use Webloyer\Domain\Model\Deployment\DeploymentRequested;

class DeployerEventServiceProvider extends EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        DeploymentRequested::class => [
            LaravelDeploymentRequestedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
