<?php

declare(strict_types=1);

namespace Common\Infra\App\Notification;

use Common\Infra\App\Notification\LaravelEvent;
use stdClass;

abstract class LaravelEventListener
{
    /**
     * Handle the event.
     *
     * @param LaravelEvent $laravelEvent
     * @return void
     */
    public function handle(LaravelEvent $laravelEvent): void
    {
        if (!$this->listensTo($laravelEvent->typeName())) {
            return;
        }
        $this->perform(json_decode($laravelEvent->eventBody()));
    }

    /**
     * @param string $typeName
     * @return bool
     */
    abstract protected function listensTo(string $typeName): bool;

    /**
     * @param stdClass $event
     * @return void
     */
    abstract protected function perform(stdClass $event): void;
}
