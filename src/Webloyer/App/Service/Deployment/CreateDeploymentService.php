<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Webloyer\Domain\Model\Deployment\{
    Deployment,
    DeploymentStatus,
    DeploymentTask,
};

class CreateDeploymentService extends DeploymentService
{
    /**
     * @param DeployRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        $deployment = Deployment::of(
            $request->getProjectId(),
            $request->getNumber(),
            DeploymentTask::rollback()->value(),
            DeploymentStatus::queued()->value(),
            '',
            $request->getExecutor(),
            'now',
            null,
            null
        );
        $this->requestDeployment($deployment);
        $this->deploymentRepository->save($deployment);
    }
}
