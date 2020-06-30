<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Webloyer\Domain\Model\Deployment\{
    Deployment,
    DeploymentDoesNotExistException,
    DeploymentNumber,
};
use Webloyer\Domain\Model\Project\{
    ProjectDoesNotExistException,
    ProjectId,
};

class GetDeploymentService extends DeploymentService
{
    /**
     * @param GetDeploymentRequest $request
     * @return mixed
     * @throws ProjectDoesNotExistException
     * @throws DeploymentDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));

        $project = $this->getNonNullProject(new ProjectId($request->getProjectId()));
        $number = new DeploymentNumber($request->getNumber());
        $deployment = $this->getNonNullDeployment($project, $number);
        return $this->deploymentDataTransformer->write($deployment)->read();
    }
}
