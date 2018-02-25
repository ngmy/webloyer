<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Messaging;

use App\Jobs\RunDeployer;
use Illuminate\Contracts\Bus\Dispatcher;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Service\Deployer\DeployerDispatcherServiceInterface;

class QueueDeployerDispatcherService implements DeployerDispatcherServiceInterface
{
    private $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Give the command to run deployer
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment $deployment
     * @return void
     */
    public function dispatch(Deployment $deployment)
    {
        $this->dispatcher->dispatch(
            new RunDeployer(
                $deployment->projectId()->id(),
                $deployment->deploymentId()->id()
            )
        );
    }
}
