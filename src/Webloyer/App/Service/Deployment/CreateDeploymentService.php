<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Webloyer\Domain\Model\Deployment\{
    Deployment,
    DeploymentStatus,
    DeploymentTask,
};
use Webloyer\Domain\Model\Project\ProjectId;

class CreateDeploymentService extends DeploymentService
{
    /**
     * @param CreateDeploymentRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $deployment = Deployment::of(
            $request->getProjectId(),
            $this->deploymentRepository->nextId(new ProjectId($request->getProjectId()))->value(),
            DeploymentTask::deploy()->value(),
            DeploymentStatus::queued()->value(),
            '',
            $request->getExecutor(),
            'now',
            null,
            null
        );
        $this->requestDeployment($deployment);
        $this->deploymentRepository->save($deployment);

        return $this->deploymentDataTransformer->write($deployment)->read(); // TODO surrogateIdがない
    }
}
