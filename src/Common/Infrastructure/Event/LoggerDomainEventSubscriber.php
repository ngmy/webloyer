<?php

declare(strict_types=1);

namespace Common\Infrastructure\Event;

use Common\Domain\Model\Event\DomainEvent;
use Common\Domain\Model\Event\DomainEventSubscriber;
use Psr\Log\LoggerInterface;

class LoggerDomainEventSubscriber implements DomainEventSubscriber
{
    /** @var LoggerInterface */
    private $logger;

    /**
     * @param LoggerInterface $logger
     * @return void
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param DomainEvent $domainEvent
     * @return void
     * @see DomainEventSubscriber::handle()
     */
    public function handle(DomainEvent $domainEvent): void
    {
        $domainEventInArray = json_decode(json_encode($domainEvent), true);

        $this->logger->info(
            get_class($domainEvent),
            $domainEventInArray + [
                'name' => get_class($domainEvent),
                // 'occurred_on' => $domainEvent->occurredOn(), TODO
            ]
        );
    }

    /**
     * @param DomainEvent $domainEvent
     * @return bool
     * @see DomainEventSubscriber::isSubscribedTo()
     */
    public function isSubscribedTo(DomainEvent $domainEvent): bool
    {
        return true; // 全てのドメインイベント
    }
}
