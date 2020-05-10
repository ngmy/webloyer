<?php

declare(strict_types=1);

namespace Webloyer;

use Deployer\Domain\Model\{
    DeployerFinished,
    DeployerProgressed,
    DeployerStarted,
};
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;
use Webloyer\Domain\Model\Deployment\DeploymentCompleted;
use Webloyer\Infra\Messaging\Laravel\{
    LaravelDeployerFinishedListener,
    LaravelDeployerProgressedListener,
    LaravelDeployerStartedListener,
    LaravelDeploymentCompletedListener,
};

class WebloyerEventServiceProvider extends EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        DeploymentCompleted::class => [
            LaravelDeploymentCompletedListener::class,
        ],
        DeployerFinished::class => [
            LaravelDeployerFinishedListener::class,
        ],
        DeployerProgessed::class => [
            LaravelDeployerProgressedListener::class,
        ],
        DeployerStarted::class => [
            LaravelDeployerStartedListener::class,
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
