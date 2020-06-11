<?php

declare(strict_types=1);

namespace Webloyer\Infra\Ui\Api\JsonRpc;

use Datto\JsonRpc\Evaluator;
use Datto\JsonRpc\Exceptions\{
    ArgumentException,
    MethodException,
};
use InvalidArgumentException;
use Webloyer\App\Service\Deployment\{
    CreateDeploymentRequest,
    CreateDeploymentService,
    RollbackDeploymentRequest,
    RollbackDeploymentService,
};
use Webloyer\App\Service\User\{
    GetUserByApiTokenRequest,
    GetUserByApiTokenService,
};
use Webloyer\Domain\Model\User\User;

class Api implements Evaluator
{
    private $createDeploymentService;
    private $rollbackDeploymentService;
    private $getUserByApiTokenService;

    public function __construct(
        CreateDeploymentService $createDeploymentService,
        RollbackDeploymentService $rollbackDeploymentService,
        GetUserByApiTokenService $getUserByApiTokenService
    ) {
        $this->createDeploymentService = $createDeploymentService;
        $this->rollbackDeploymentService = $rollbackDeploymentService;
        $this->getUserByApiTokenService = $getUserByApiTokenService;
    }

    public function evaluate($method, $arguments)
    {
        if ($method == 'deploy') {
            return $this->deploy($arguments);
        }
        if ($method == 'rollback') {
            return $this->rollback($arguments);
        }
        throw new MethodException();
    }

    /**
     * Deploy a project.
     *
     * @param array $arguments
     * @return object
     */
    public function deploy(array $arguments): object
    {
        $serviceRequest = (new CreateDeploymentRequest())
            ->setProjectId($arguments['project_id'])
            ->setExecutor($this->nonNullUser()->id);
        $deployment = $this->createDeploymentService->execute($serviceRequest);
        return $deployment;
    }

    /**
     * Roll back a deployment.
     *
     * @param array $arguments
     * @return object
     */
    public function rollback(array $arguments): object
    {
        $serviceRequest = (new RollbackDeploymentRequest())
            ->setProjectId($arguments['project_id'])
            ->setExecutor($this->nonNullUser()->id);
        $deployment = $rollbackService->execute($serviceRequest);
        return $deployment;
    }

    private function nonNullUser(): object
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
        $header = $_SERVER['HTTP_AUTHORIZATION'];
        if (strpos($header, 'Bearer ') === 0) {
            return substr($header, 7);
        } else {
            throw new InvalidArgumentException();
        }
    }
}
