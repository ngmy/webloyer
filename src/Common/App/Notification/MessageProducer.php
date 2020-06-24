<?php

declare(strict_types=1);

namespace Common\App\Notification;

use Common\Domain\Model\Event\DomainEvent;

/**
 * @codeCoverageIgnore
 */
interface MessageProducer
{
    /**
     * @param string $notificationMessage
     * @param string $notificationType
     * @return void
     */
    public function send(string $notificationMessage, string $notificationType): void;
}
