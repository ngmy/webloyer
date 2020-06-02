<?php

declare(strict_types=1);

namespace Webloyer\App\DataTransformer\Deployment;

use Webloyer\App\DataTransformer\User\UserDataTransformer;
use Webloyer\Domain\Model\Deployment\{
    Deployment,
    DeploymentInterest,
    DeploymentService,
};
use Webloyer\Domain\Model\User\UserId;

class DeploymentDtoDataTransformer implements DeploymentDataTransformer
{
    private $deployment;
    private $deploymentService;

    public function __construct(DeploymentService $deploymentService)
    {
        $this->deploymentService = $deploymentService;
    }

    /**
     * @param Deployment $deployment
     * @return self
     */
    public function write(Deployment $deployment): self
    {
        $this->deployment = $deployment;
        return $this;
    }

    /**
     * @return object
     */
    public function read()
    {
        $dto = new class implements DeploymentInterest {
            public function informProjectId(string $projectId): void
            {
                $this->projectId = $projectId;
            }
            public function informNumber(int $number): void
            {
                $this->number = $number;
            }
            public function informTask(string $task): void
            {
                $this->task = $task;
            }
            public function informStatus(string $status): void
            {
                $this->status = $status;
            }
            public function informLog(string $log): void
            {
                $this->log = $log;
            }
            public function informExecutor(string $executor): void
            {
                $this->executor = $executor;
            }
            public function informStartDate(?string $startDate): void
            {
                $this->startDate = $startDate;
            }
            public function informRequestDate(string $requestDate): void
            {
                $this->requestDate = $requestDate;
            }
            public function informFinishDate(?string $finishDate): void
            {
                $this->finishDate = $finishDate;
            }
        };
        $this->deployment->provide($dto);

        if (isset($this->userDataTransformer)) {
            $user = $this->deploymentService->userFrom(new UserId($this->deployment->executor()));
            $dto->user = $this->userDataTransformer->write($user)->read();
        }

        $dto->surrogateId = $this->deployment->surrogateId();
        $dto->createdAt = $this->deployment->createdAt();
        $dto->updatedAt = $this->deployment->updatedAt();

        return $dto;
    }

    /**
     * @param UserDataTransformer $userDataTransformer
     * @return self
     */
    public function setUserDataTransformer(UserDataTransformer $userDataTransformer): self
    {
        $this->userDataTransformer = $userDataTransformer;
        return $this;
    }
}
