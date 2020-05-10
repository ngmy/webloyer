<?php

declare(strict_types=1);

namespace Common\App\Service;

/**
 * @codeCoverageIgnore
 */
interface TransactionalSession
{
    /**
     * @param callable $operation
     * @return mixed
     */
    public function executeAtomically(callable $operation);
}
