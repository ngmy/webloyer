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
    /** @var Deployment */
    private $deployment;
    /** @var DeploymentService */
    private $deploymentService;
    /** @var UserDataTransformer */
    private $userDataTransformer;

    /**
     * @param DeploymentService $deploymentService
     * @return void
     */
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
            /** @var string */
            public $projectId;
            /** @var int */
            public $number;
            /** @var string */
            public $task;
            /** @var string */
            public $status;
            /** @var string */
            public $log;
            /** @var string */
            public $executor;
            /** @var string */
            public $requestDate;
            /** @var string|null */
            public $startDate;
            /** @var string|null */
            public $finishDate;
            /** @var object|null */
            public $user;
            /** @var int */
            public $surrogateId;
            /** @var string */
            public $createdAt;
            /** @var string */
            public $updatedAt;
            /**
             * @param string $projectId
             * @return void
             */
            public function informProjectId(string $projectId): void
            {
                $this->projectId = $projectId;
            }
            /**
             * @param int $number
             * @return void
             */
            public function informNumber(int $number): void
            {
                $this->number = $number;
            }
            /**
             * @param string $task
             * @return void
             */
            public function informTask(string $task): void
            {
                $this->task = $task;
            }
            /**
             * @param string $status
             * @return void
             */
            public function informStatus(string $status): void
            {
                $this->status = $status;
            }
            /**
             * @param string $log
             * @return void
             */
            public function informLog(string $log): void
            {
                $this->log = $log;
            }
            /**
             * @param string $executor
             * @return void
             */
            public function informExecutor(string $executor): void
            {
                $this->executor = $executor;
            }
            /**
             * @param string $requestDate
             * @return void
             */
            public function informRequestDate(string $requestDate): void
            {
                $this->requestDate = $requestDate;
            }
            /**
             * @param string|null $startDate
             * @return void
             */
            public function informStartDate(?string $startDate): void
            {
                $this->startDate = $startDate;
            }
            /**
             * @param string|null $finishDate
             * @return void
             */
            public function informFinishDate(?string $finishDate): void
            {
                $this->finishDate = $finishDate;
            }
        };
        $this->deployment->provide($dto);

        if (isset($this->userDataTransformer)) {
            $user = $this->deploymentService->userFrom(new UserId($this->deployment->executor()));
            $dto->user = $user ? $this->userDataTransformer->write($user)->read() : null;
        }

        $dto->surrogateId = $this->deployment->surrogateId();
        assert(!is_null($this->deployment->createdAt()));
        $dto->createdAt = $this->deployment->createdAt();
        assert(!is_null($this->deployment->updatedAt()));
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
