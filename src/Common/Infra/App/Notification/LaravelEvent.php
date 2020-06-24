<?php

declare(strict_types=1);

namespace Common\Infra\App\Notification;

class LaravelEvent
{
    /** @var string */
    private $typeName;
    /** @var string */
    private $eventBody;

    /**
     * @param string $typeName
     * @param string $eventBody
     * @return void
     */
    public function __construct(string $typeName, string $eventBody)
    {
        $this->typeName = $typeName;
        $this->eventBody = $eventBody;
    }

    /**
     * @return string
     */
    public function typeName(): string
    {
        return $this->typeName;
    }

    /**
     * @return string
     */
    public function eventBody(): string
    {
        return $this->eventBody;
    }
}
