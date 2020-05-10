<?php

declare(strict_types=1);

namespace Common\App\Service;

interface TransactionalSession
{
    /**
     * @param callable $operation
     * @return mixed
     */
    public function executeAtomically(callable $operation);
}
