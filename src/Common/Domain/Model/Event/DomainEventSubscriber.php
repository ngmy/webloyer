<?php

declare(strict_types=1);

namespace Common\Domain\Model\Event;

/**
 * @codeCoverageIgnore
 */
interface DomainEventSubscriber
{
    /**
     * @param DomainEvent $domainEvent
     * @return void
     */
    public function handle(DomainEvent $domainEvent): void;

    /**
     * @param DomainEvent $domainEvent
     * @return bool
     */
    public function isSubscribedTo(DomainEvent $domainEvent): bool;
}
