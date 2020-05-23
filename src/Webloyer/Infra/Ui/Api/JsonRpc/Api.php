<?php

declare(strict_types=1);

namespace Webloyer\Infra\Ui\Api\JsonRpc;

use InvalidArgumentException;
use Webloyer\App\Service\Deployment\{
    CreateDeploymentRequest,
    CreateDeploymentSerivie,
    RollbackDeploymentRequest,
    RollbackDeploymentSerivie,
};
use Webloyer\App\Service\User\{
    GetUserByApiTokenRequest,
    GetUserByApiTokenService,
};
use Webloyer\Domain\Model\Deployment\Deployment;
use Webloyer\Domain\Model\User\User;

class Api
{
    private $createDeploymentService;
    private $rollbackDeploymentService;
    private $getUserByApiTokenService;

    public function __construt(
        CreateDeploymentSerivie $createDeploymentService,
        RollbackDeploymentSerivie $rollbackDeploymentService,
        GetUserByApiTokenService $getUserByApiTokenService
    ) {
        $this->createDeploymentService = $createDeploymentService;
        $this->rollbackDeploymentService = $rollbackDeploymentService;
        $this->getUserByApiTokenService = $getUserByApiTokenService;
    }

    /**
     * Deploy a project.
     *
     * @param int $projectId
     * @return Deployment
     */
    public function deploy($projectId)
    {
        $serviceRequest = (new CreateDeploymentRequest())
            ->setProjectId($projectId)
            ->setExecutor($this->nonNullUser->id());
        $deployment = $this->createDeploymentService->execute($serviceRequest);
        return $deployment;
    }

    /**
     * Roll back a deployment.
     *
     * @param int $projectId
     * @return Deployment
     */
    public function rollback($projectId)
    {
        $serviceRequest = (new RollbackDeploymentRequest())
            ->setProjectId($projectId)
            ->setExecutor($this->nonNullUser->id());
        $deployment = $rollbackService->execute($serviceRequest);
        return $deployment;
    }

    private function nonNullUser(): User
    {
        $serviceRequest = (new GetUserByApiTokenRequest())
            ->setApiToken($this->nonNullApiToken());
        $user = $this->getUserByApiTokenService->execute($serviceRequest);
        if (is_null($user)) {
            throw new InvalidArgumentException();
        }
        return $user;
    }

    private function nonNullApiToken(): string
    {
        $header = $this->server['HTTP_AUTHORIZATION'];
        if (strpos($header, 'Bearer ') === 0) {
            return substr($header, 7);
        } else {
            throw new InvalidArgumentException();
        }
    }
}
