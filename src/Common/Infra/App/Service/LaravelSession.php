<?php

declare(strict_types=1);

namespace Common\Infra\App\Service;

use Common\App\Service\TransactionalSession;
use DB;

class LaravelSession implements TransactionalSession
{
    /**
     * @param callable $operation
     * @return mixed
     * @see TransactionalSession::executeAtomically()
     */
    public function executeAtomically(callable $operation)
    {
        return DB::trsancation($operation);
    }
}
