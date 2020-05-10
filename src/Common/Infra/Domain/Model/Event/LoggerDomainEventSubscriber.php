<?php

declare(strict_types=1);

namespace Common\Infra\Domain\Model\Event;

use Common\Domain\Model\Event\{
    DomainEvent,
    DomainEventSubscriber,
};
use InvalidArgumentException;
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
        $domainEventInJson = json_encode($domainEvent);
        if (!$domainEventInJson) {
            throw new InvalidArgumentException(
                'json_encode failed.' . PHP_EOL .
                'Value: ' . var_export($domainEvent, true)
            );
        }
        $domainEventInArray = json_decode($domainEventInJson, true);

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
