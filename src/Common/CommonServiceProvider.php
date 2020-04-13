<?php

declare(strict_types=1);

namespace Common;

use Common\Domain\Model\Event\DomainEventPublisher;
use Common\Infra\Event\LaravelEventDomainEventSubscriber;
use Common\Infra\Event\LoggerDomainEventSubscriber;
use Illuminate\Support\ServiceProvider;

class CommonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(DomainEventPublisher::class, function () {
            return DomainEventPublisher::getInstance();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(): void
    {
        $domainEventPublisher = $this->app->make(DomainEventPublisher::class);
        $domainEventPublisher->subscribe($this->app->make(LaravelEventDomainEventSubscriber::class));
        $domainEventPublisher->subscribe($this->app->make(LoggerDomainEventSubscriber::class));
    }
}
