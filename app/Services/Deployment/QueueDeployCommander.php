<?php
declare(strict_types=1);

namespace App\Services\Deployment;

use App\Jobs\Deploy;
use App\Jobs\Rollback;
use App\Jobs\Unlock;
use Illuminate\Contracts\Bus\Dispatcher;

/**
 * Class QueueDeployCommander
 * @package App\Services\Deployment
 */
class QueueDeployCommander implements DeployCommanderInterface
{

    /**
     * @var Dispatcher
     */
    protected Dispatcher $dispatcher;

    /**
     * QueueDeployCommander constructor.
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Give the command to deploy
     *
     * @param mixed $deployment
     * @return bool|void
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
     * @return bool|void
     */
    public function rollback($deployment)
    {
        $this->dispatcher->dispatch(
            new Rollback($deployment)
        );
    }

    /**
     * Give the command to unlock
     *
     * @param mixed $deployment
     * @return bool|void
     */
    public function unlock($deployment)
    {
        $this->dispatcher->dispatch(
            new Unlock($deployment)
        );
    }
}
