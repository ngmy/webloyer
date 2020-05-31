<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Deployment;

use Webloyer\Domain\Model\Deployment\Deployments;

/**
 * @codeCoverageIgnore
 */
interface DeploymentsDataTransformer
{
    /**
     * @param Deployments $deployments
     * @return self
     */
    public function write(Deployments $deployments): self;
    /**
     * @return mixed
     */
    public function read();
    /**
     * @return DeploymentDataTransformer
     */
    public function deploymentDataTransformer(): DeploymentDataTransformer;
}
