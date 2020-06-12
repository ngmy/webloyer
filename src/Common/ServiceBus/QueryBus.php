<?php

declare(strict_types=1);

namespace Common\ServiceBus;

class QueryBus
{
    /** @var array<string, object> */
    private $queryHandlers = [];

    /**
     * @param object $query
     * @return mixed
     */
    public function handle(object $query)
    {
        $queryClass = get_class($query);

        if (!isset($this->queryHandlers[$queryClass])) {
            throw new HandlerNotFoundException($queryClass);
        }

        $queryHandler = $this->queryHandlers[$queryClass];
        return $queryHandler->handle($query);
    }

    /**
     * @param object $queryHandler
     * @return void
     */
    public function register(object $queryHandler): void
    {
        $queryHandlerClass = get_class($queryHandler);
        $queryClass = str_replace('Handler', '', $queryHandlerClass);
        $this->queryHandlers[$queryClass] = $queryHandler;
    }
}
