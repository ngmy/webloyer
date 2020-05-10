<?php

declare(strict_types=1);

namespace Common;

use Common\Domain\Model\Event\DomainEventPublisher;
use Common\Domain\Model\Identity\IdGenerator;
use Common\Infra\Domain\Model\Identity\UuidIdGenerator;
use Common\Infra\Domain\Model\Event\{
    LaravelEventDomainEventSubscriber,
    LoggerDomainEventSubscriber,
};
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
        $this->app->bind(IdGenerator::class, UuidIdGenerator::class);
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
