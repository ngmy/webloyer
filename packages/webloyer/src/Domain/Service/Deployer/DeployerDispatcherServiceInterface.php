<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Service\Deployer;

use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;

interface DeployerDispatcherServiceInterface
{
    /**
     * Give the command to run deployer
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment $deployment
     * @return void
     */
    public function dispatch(Deployment $deployment);
}
