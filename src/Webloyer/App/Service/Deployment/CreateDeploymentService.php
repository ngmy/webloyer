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

class CreateDeploymentService extends DeploymentService
{
    /**
     * @param CreateDeploymentRequest $request
     * @return mixed
     * @throws ProjectDoesNotExistException
     * @throws RecipeDoesNotExistException
     * @throws ServerDoesNotExistException
     * @throws UserDoesNotExistException
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

        return $this->deploymentDataTransformer->write($deployment)->read();
    }
}
