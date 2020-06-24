<?php

declare(strict_types=1);

namespace Webloyer\Infra\Framework\Laravel\App\Providers;

use Common\Domain\Model\Event\DomainEventPublisher;
use Common\Infra\App\Notification\LaravelEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Illuminate\Support\Facades\Event;
use Webloyer\Domain\Model\Deployment\DeploymentCompletedSubscriber;
use Webloyer\Infra\Framework\Laravel\App\Listeners\{
    DeployerFinishedListener,
    DeployerProgressedListener,
    DeployerStartedListener,
};

class WebloyerEventServiceProvider extends EventServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<string, list<string>>
     */
    protected $listen = [
        LaravelEvent::class => [
            DeployerFinishedListener::class,
            DeployerProgressedListener::class,
            DeployerStartedListener::class,
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
        $domainEventPublisher->subscribe($this->app->make(DeploymentCompletedSubscriber::class));
    }
}
