<?php

declare(strict_types=1);

namespace Common\Infra\App\Service;

use Closure;
use Common\App\Service\TransactionalSession;
use Illuminate\Support\Facades\DB;

class LaravelSession implements TransactionalSession
{
    /**
     * @param callable $operation
     * @return mixed
     * @see TransactionalSession::executeAtomically()
     */
    public function executeAtomically(callable $operation)
    {
        return DB::transaction(Closure::fromCallable($operation));
    }
}
