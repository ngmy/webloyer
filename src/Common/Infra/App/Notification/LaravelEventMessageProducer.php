<?php

declare(strict_types=1);

namespace Common\Infra\App\Notification;

use Common\App\Notification\MessageProducer;

class LaravelEventMessageProducer implements MessageProducer
{
    /**
     * @param string $notificationMessage
     * @param string $notificationType
     * @return void
     */
    public function send(string $notificationMessage, string $notificationType): void
    {
        $laravelEvent = new LaravelEvent(
            $notificationType,
            $notificationMessage,
        );

        event($laravelEvent);
    }
}
