<?php

declare(strict_types=1);

namespace Common\Infra\Event;

use Common\Domain\Model\Event\DomainEvent;
use Common\Domain\Model\Event\DomainEventSubscriber;
use Common\Domain\Model\Event\PublishableDomainEvent;

class LaravelEventDomainEventSubscriber implements DomainEventSubscriber
{
    /**
     * @param DomainEvent $domainEvent
     * @return void
     * @see DomainEventSubscriber::handle()
     */
    public function handle(DomainEvent $domainEvent): void
    {
        $this->dispatchLaravelEvent($domainEvent);
    }

    /**
     * @param DomainEvent $domainEvent
     * @return void
     */
    public function dispatchLaravelEvent(DomainEvent $domainEvent): void
    {
        event($domainEvent);
    }

    /**
     * @param DomainEvent $domainEvent
     * @return bool
     * @see DomainEventSubscriber::isSubscribedTo()
     */
    public function isSubscribedTo(DomainEvent $domainEvent): bool
    {
        return $domainEvent instanceof PublishableDomainEvent;
    }
}
