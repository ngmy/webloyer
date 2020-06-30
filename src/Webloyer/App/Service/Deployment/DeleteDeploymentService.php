<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Webloyer\Domain\Model\Deployment\{
    DeploymentDoesNotExistException,
    DeploymentNumber,
};
use Webloyer\Domain\Model\Project\{
    ProjectDoesNotExistException,
    ProjectId,
};

class DeleteDeploymentService extends DeploymentService
{
    /**
     * @param DeleteDeploymentRequest $request
     * @return void
     * @throws ProjectDoesNotExistException
     * @throws DeploymentDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));

        $project = $this->getNonNullProject(new ProjectId($request->getProjectId()));
        $number = new DeploymentNumber($request->getNumber());

        $deployment = $this->getNonNullDeployment($project, $number);

        $this->deploymentRepository->remove($deployment);
    }
}
