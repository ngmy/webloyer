<?php

declare(strict_types=1);

namespace Deployer\Infra\Framework\Laravel\App\Providers;

use Common\Infra\App\Notification\LaravelEvent;
use Deployer\Infra\Framework\Laravel\App\Listeners\DeploymentRequestedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;

class DeployerEventServiceProvider extends EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<string, list<string>>
     */
    protected $listen = [
        LaravelEvent::class => [
            DeploymentRequestedListener::class,
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
