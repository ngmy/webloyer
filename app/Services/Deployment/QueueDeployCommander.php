<?php

namespace App\Services\Deployment;

use App\Jobs\Deploy;
use App\Jobs\Rollback;

use Illuminate\Contracts\Bus\Dispatcher;

class QueueDeployCommander implements DeployCommanderInterface
{
    protected $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Give the command to deploy
     *
     * @param mixed $deployment
     * @return boolean
     */
    public function deploy($deployment)
    {
        $this->dispatcher->dispatch(
            new Deploy($deployment)
        );
    }

    /**
     * Give the command to rollback
     *
     * @param mixed $deployment
     * @return boolean
     */
    public function rollback($deployment)
    {
        $this->dispatcher->dispatch(
            new Rollback($deployment)
        );
    }
}
