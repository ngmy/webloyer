<?php

declare(strict_types=1);

namespace Webloyer\App\Service\Deployment;

use Webloyer\Domain\Model\Deployment\{
    Deployment,
    DeploymentStatus,
    DeploymentTask,
};
use Webloyer\Domain\Model\Project\{
    ProjectDoesNotExistException,
    ProjectId,
};
use Webloyer\Domain\Model\Recipe\RecipeDoesNotExistException;
use Webloyer\Domain\Model\Server\ServerDoesNotExistException;
use Webloyer\Domain\Model\User\UserDoesNotExistException;

class RollbackDeploymentService extends DeploymentService
{
    /**
     * @param RollbackDeploymentRequest $request
     * @return mixed
     * @throws ProjectDoesNotExistException
     * @throws RecipeDoesNotExistException
     * @throws ServerDoesNotExistException
     * @throws UserDoesNotExistException
     */
    public function execute($request = null)
    {
        assert(!is_null($request));

        $project = $this->getNonNullProject(new ProjectId($request->getProjectId()));
        $deployment = Deployment::of(
            $request->getProjectId(),
            $this->deploymentRepository->nextId($project)->value(),
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

        return $this->deploymentDataTransformer->write($deployment)->read();
    }
}
