<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Deployment;

use Webloyer\Domain\Model\Deployment\{
    Deployment,
    Deployments,
};

class DeploymentsDtoDataTransformer implements DeploymentsDataTransformer
{
    /** @var Deployments */
    private $deployments;
    /** @var DeploymentDtoDataTransformer */
    private $deploymentDataTransformer;

    public function __construct(DeploymentDtoDataTransformer $deploymentDataTransformer)
    {
        $this->deploymentDataTransformer = $deploymentDataTransformer;
    }

    /**
     * @param Deployments $deployments
     * @return self
     */
    public function write(Deployments $deployments): self
    {
        $this->deployments = $deployments;
        return $this;
    }

    /**
     * @return array<int, object>
     */
    public function read()
    {
        return array_map(function (Deployment $deployment): object {
            return $this->deploymentDataTransformer->write($deployment)->read();
        }, $this->deployments->toArray());
    }

    /**
     * @return DeploymentDataTransformer
     */
    public function deploymentDataTransformer(): DeploymentDataTransformer
    {
        return $this->deploymentDataTransformer;
    }
}
