<?php

declare(strict_types=1);

namespace Common\Domain\Model\Event;

use Common\App\Notification\MessageProducer;
use JMS\Serializer\SerializerInterface;

class ForwardDomainEventSubscriber implements DomainEventSubscriber
{
    /** @var SerializerInterface */
    private $serializer;
    /** @var MessageProducer */
    private $messageProducer;

    /**
     * @param SerializerInterface $serializer
     * @param MessageProducer     $messageProducer
     * @return void
     */
    public function __construct(
        SerializerInterface $serializer,
        MessageProducer $messageProducer
    ) {
        $this->serializer = $serializer;
        $this->messageProducer = $messageProducer;
    }

    /**
     * @param DomainEvent $domainEvent
     * @return void
     * @see DomainEventSubscriber::handle()
     */
    public function handle(DomainEvent $domainEvent): void
    {
        $this->messageProducer->send(
            $this->serializer->serialize($domainEvent, 'json'),
            get_class($domainEvent)
        );
    }

    /**
     * @param DomainEvent $domainEvent
     * @return bool
     * @see DomainEventSubscriber::isSubscribedTo()
     */
    public function isSubscribedTo(DomainEvent $domainEvent): bool
    {
        return $domainEvent instanceof PublishNowDomainEvent;
    }
}
