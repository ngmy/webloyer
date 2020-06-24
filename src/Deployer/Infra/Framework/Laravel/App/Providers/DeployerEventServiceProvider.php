<?php

declare(strict_types=1);

namespace Deployer\Infra\Framework\Laravel\App\Providers;

use Common\Domain\Model\Event\DomainEventPublisher;
use Deployer\Domain\Model\{
    DeployerFinishedSubscriber,
    DeployerProgressedSubscriber,
    DeployerStartedSubscriber,
};
use Deployer\Infra\Framework\Laravel\App\Listeners\DeploymentRequestedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;
use Webloyer\Domain\Model\Deployment\DeploymentRequested;

class DeployerEventServiceProvider extends EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<string, list<string>>
     */
    protected $listen = [
        DeploymentRequested::class => [
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

        $domainEventPublisher = $this->app->make(DomainEventPublisher::class);
        $domainEventPublisher->subscribe($this->app->make(DeployerFinishedSubscriber::class));
        $domainEventPublisher->subscribe($this->app->make(DeployerProgressedSubscriber::class));
        $domainEventPublisher->subscribe($this->app->make(DeployerStartedSubscriber::class));
    }
}
