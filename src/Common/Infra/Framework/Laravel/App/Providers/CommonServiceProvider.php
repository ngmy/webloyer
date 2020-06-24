<?php

declare(strict_types=1);

namespace Common\Infra\Framework\Laravel\App\Providers;

use Common\App\Notification\MessageProducer;
use Common\App\Service\TransactionalSession;
use Common\Domain\Model\Event\{
    DomainEventPublisher,
    ForwardDomainEventSubscriber,
    LoggerDomainEventSubscriber,
};
use Common\Domain\Model\Identity\IdGenerator;
use Common\Infra\App\Notification\LaravelEventMessageProducer;
use Common\Infra\App\Service\LaravelSession;
use Common\Infra\Domain\Model\Identity\UuidIdGenerator;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use JMS\Serializer\SerializerBuilder;

class CommonServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(TransactionalSession::class, LaravelSession::class);
        $this->app->bind(IdGenerator::class, UuidIdGenerator::class);
        $this->app->singleton(DomainEventPublisher::class, function (): DomainEventPublisher {
            return DomainEventPublisher::getInstance();
        });
        $this->app->bind(MessageProducer::class, LaravelEventMessageProducer::class);
        $this->app->bind(ForwardDomainEventSubscriber::class, function (Application $app): ForwardDomainEventSubscriber {
            return new ForwardDomainEventSubscriber(
                SerializerBuilder::create()->build(),
                $app->make(MessageProducer::class)
            );
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
        $domainEventPublisher->subscribe($this->app->make(ForwardDomainEventSubscriber::class));
        $domainEventPublisher->subscribe($this->app->make(LoggerDomainEventSubscriber::class));
    }
}
