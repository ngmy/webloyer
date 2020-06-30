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

class FinishDeploymentService extends DeploymentService
{
    /**
     * @param FinishDeploymentRequest $request
     * @return mixed
     * @throws ProjectDoesNotExistException
     * @throws DeploymentDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));

        $project = $this->getNonNullProject(new ProjectId($request->getProjectId()));
        $deployment = $this->getNonNullDeployment($project, new DeploymentNumber($request->getNumber()));
        $deployment->changeLog($request->getLog())
            ->changeStatus($request->getStatus() == 0 ? 'succeeded' : 'failed')
            ->changeFinishDate($request->getFinishDate())
            ->complete();
        $this->deploymentRepository->save($deployment);

        return $this->deploymentDataTransformer->write($deployment)->read();
    }
}
