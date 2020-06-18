<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Webloyer\Domain\Model\Deployment\Deployments;
use Webloyer\Domain\Model\Project\{
    ProjectDoesNotExistException,
    ProjectId,
};

class GetDeploymentsService extends DeploymentService
{
    /**
     * @param GetDeploymentsRequest $request
     * @return mixed
     * @throws ProjectDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $project = $this->getNonNullProject(new ProjectId($request->getProjectId()));
        $deployments = $this->deploymentRepository->findAllByProjectId(new ProjectId($request->getProjectId()));
        return $this->deploymentsDataTransformer->write($deployments)->read();
    }
}
