<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Webloyer\Domain\Model\Deployment\Deployments;
use Webloyer\Domain\Model\Project\ProjectId;

class GetDeploymentsService extends DeploymentService
{
    /**
     * @param GetDeploymentsRequest $request
     * @return mixed
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $deployments = $this->deploymentRepository->findAllByProjectId(new ProjectId($request->getProjectId()));
        return $this->deploymentsDataTransformer->write($deployments)->read();
    }
}
