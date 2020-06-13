<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Webloyer\Domain\Model\Deployment\DeploymentNumber;
use Webloyer\Domain\Model\Project\ProjectId;

class DeleteDeploymentService extends DeploymentService
{
    /**
     * @param DeleteDeploymentRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $projectId = new ProjectId($request->getProjectId());
        $number = new DeploymentNumber($request->getNumber());
        $deployment = $this->getNonNullDeployment($projectId, $number);
        $this->deploymentRepository->remove($deployment);
    }
}
