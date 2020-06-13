<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use DateTimeImmutable;
use Webloyer\Domain\Model\Deployment\OldDeploymentSpecification;

class DeleteOldDeploymentsService extends DeploymentService
{
    /**
     * @param DeleteOldDeploymentsRequest $request
     * @return void
     */
    public function execute($request = null)
    {
        assert(!is_null($request));
        $spec = new OldDeploymentSpecification(
            $this->deploymentRepository,
            $this->projectRepository,
            new DateTimeImmutable($request->getDateTime())
        );

        $oldDeployments = $this->deploymentRepository->satisfyingDeployments($spec);

        $this->deploymentRepository->removeAll($oldDeployments);
    }
}
