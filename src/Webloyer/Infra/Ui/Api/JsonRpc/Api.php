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
    /** @var CreateDeploymentService */
    private $createDeploymentService;
    /** @var RollbackDeploymentService */
    private $rollbackDeploymentService;
    /** @var GetUserByApiTokenService */
    private $getUserByApiTokenService;

    /**
     * @param CreateDeploymentService $createDeploymentService
     * @param RollbackDeploymentService $rollbackDeploymentService
     * @param GetUserByApiTokenService $getUserByApiTokenService
     * @return void
     */
    public function __construct(
        CreateDeploymentService $createDeploymentService,
        RollbackDeploymentService $rollbackDeploymentService,
        GetUserByApiTokenService $getUserByApiTokenService
    ) {
        $this->createDeploymentService = $createDeploymentService;
        $this->rollbackDeploymentService = $rollbackDeploymentService;
        $this->getUserByApiTokenService = $getUserByApiTokenService;
    }

    /**
     * @param string               $method
     * @param array<string, mixed> $arguments
     */
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
     * @param array<string, mixed> $arguments
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
     * @param array<string, mixed> $arguments
     * @return object
     */
    public function rollback(array $arguments): object
    {
        $serviceRequest = (new RollbackDeploymentRequest())
            ->setProjectId($arguments['project_id'])
            ->setExecutor($this->nonNullUser()->id);
        $deployment = $this->rollbackDeploymentService->execute($serviceRequest);
        return $deployment;
    }

    /**
     * @return object
     */
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

    /**
     * @return string
     */
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
