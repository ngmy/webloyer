<?php

declare(strict_types=1);

namespace Common\ServiceBus;

use Exception;

class HandlerNotFoundException extends Exception
{
    /**
     * @param string $queryClass
     * @return void
     */
    public function __construct(string $queryClass)
    {
        parent::__construct(sprintf('Unable to find a registered handler for "%s".', $queryClass));
    }
}
