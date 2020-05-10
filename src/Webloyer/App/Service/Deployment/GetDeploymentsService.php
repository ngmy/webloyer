<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Webloyer\Domain\Model\Deployment\Deployments;

class GetDeploymentsService extends DeploymentService
{
    /**
     * @param GetDeploymentsRequest $request
     * @return Deployments
     */
    public function execute($request = null)
    {
        return $this->deploymentRepository->findAllByPage($request->getPage(), $request->getPerPage());
    }
}
