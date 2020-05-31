<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Webloyer\Domain\Model\Deployment\{
    Deployment,
    DeploymenNumber,
};
use Webloyer\Domain\Model\Project\ProjectId;

class GetDeploymentService extends DeploymentService
{
    /**
     * @param GetDeploymentRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        $projectId = new ProjectId($request->getProjectId());
        $number = new DeploymentNumber($request->getNumber());
        $deployment = $this->getNonNullDeployment($projectId, $number);
        return $this->deploymentDataTransformer->write($deployment)->read();
    }
}
