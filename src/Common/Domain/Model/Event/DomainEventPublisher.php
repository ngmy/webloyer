<?php

declare(strict_types=1);

namespace Common\Domain\Model\Event;

use BadMethodCallException;

class DomainEventPublisher
{
    /** @var self */
    private static $instance;
    /** @var array<i, DomainEventSubscriber> */
    private $subscribers = [];
    /** @var int */
    private $id = 0;

    /**
     * @return self
     */
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param DomainEventSubscriber $domainEventSubscriber
     * @return int
     */
    public function subscribe(DomainEventSubscriber $domainEventSubscriber): int
    {
        $id = $this->id;
        $this->subscribers[$id] = $domainEventSubscriber;
        $this->id++;

        return $id;
    }

    /**
     * @param int $id
     * @return DomainEventSubscriber|null
     */
    public function getSubscriberOfId(int $id): ?DomainEventSubscriber
    {
        return $this->subscribers[$id] ?? null;
    }

    /**
     * @param int $id
     * @return void
     */
    public function unsubscribe(int $id): void
    {
        unset($this->subscribers[$id]);
    }

    /**
     * @return void
     */
    public function unsubscribeAll(): void
    {
        foreach (array_keys($this->subscribers) as $id) {
            $this->unsubscribe($id);
        }
    }

    /**
     * @param DomainEvent $domainEvent
     * @return void
     */
    public function publish(DomainEvent $domainEvent): void
    {
        foreach ($this->subscribers as $subscriber) {
            if ($subscriber->isSubscribedTo($domainEvent)) {
                $subscriber->handle($domainEvent);
            }
        }
    }

    /**
     * @return void
     * @throws BadMethodCallException
     */
    public function __clone()
    {
        throw new BadMethodCallException(
            'This class is a singleton class, you are not allowed to clone it.' . PHP_EOL .
            'Please call ' . get_class($this) . '::getInstance() to get a reference to ' .
            'the only instance of the ' . get_class($this) . ' class.'
        );
    }

    /**
     * @return void
     * @throws BadMethodCallException
     */
    public function __wakeup(): void
    {
        throw new BadMethodCallException(
            'This class is a singleton class, you are not allowed to unserialize ' .
            'it as this could create a new instance of it.' . PHP_EOL .
            'Please call ' . get_class($this) . '::getInstance() to get a reference to ' .
            'the only instance of the ' . get_class($this) . ' class.'
        );
    }

    /**
     * @return void
     */
    private function __construct()
    {
    }
}
