<?php

namespace Ngmy\Webloyer\Webloyer\Application\Deployment;

use DateTimeImmutable;
use DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Ngmy\Webloyer\Common\QueryObject\Direction;
use Ngmy\Webloyer\Common\QueryObject\Order;
use Ngmy\Webloyer\Common\QueryObject\Pagination;
use Ngmy\Webloyer\Common\QueryObject\QueryObject;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentCriteria;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Status;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Task;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\OldDeploymentSpecification;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;

class DeploymentService
{
    private $projectService;

    private $deploymentRepository;

    /**
     * Create a new application service instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Application\Project\ProjectService                    $projectService
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentRepositoryInterface $deploymentRepository
     * @return void
     */
    public function __construct(ProjectService $projectService, DeploymentRepositoryInterface $deploymentRepository)
    {
        $this->projectService = $projectService;
        $this->deploymentRepository = $deploymentRepository;
    }

    /**
     * Get next identity.
     *
     * @param int $projectId
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentId
     */
    public function getNextIdentity(int $projectId): DeploymentId
    {
        return $this->deploymentRepository->nextIdentity(
            $this->projectService->getProjectById($projectId)
        );
    }

    /**
     * Get deployments by page.
     *
     * @param int $projectId
     * @param int $page
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function getDeploymentsByPage(int $projectId, int $page = 1, int $perPage = 10): LengthAwarePaginator
    {
        $criteria = new DeploymentCriteria(new ProjectId($projectId));
        $order = new Order('deployments.created_at', Direction::desc());
        $pagination = new Pagination($page, $perPage);
        $queryObject = new QueryObject();
        $queryObject->setCriteria($criteria)
            ->addOrder($order)
            ->setPagination($pagination);
        return $this->deploymentRepository->deployments($queryObject);
    }

    /**
     * Get a deployment by id.
     *
     * @param int $projectId
     * @param int $deploymentId
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment|null
     */
    public function getDeploymentById(int $projectId, int $deploymentId): ?Deployment
    {
        return $this->deploymentRepository->deploymentOfId(
            $this->projectService->getProjectById($projectId),
            new DeploymentId($deploymentId)
        );
    }

    /**
     * Get a last deployment.
     *
     * @param int $projectId
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment|null
     */
    public function getLastDeployment(int $projectId): ?Deployment
    {
        $criteria = new DeploymentCriteria(new ProjectId($projectId));
        $order = new Order('deployments.created_at', Direction::desc());
        $pagination = new Pagination(1, 1);
        $queryObject = new QueryObject();
        $queryObject->setCriteria($criteria)
            ->addOrder($order)
            ->setPagination($pagination);
        $deployments = $this->deploymentRepository->deployments($queryObject);
        if ($deployments->isEmpty()) {
            return null;
        }
        return $deployments[0];
    }

    /**
     * Create a deployment.
     *
     * @param int         $projectId
     * @param int         $deploymentId
     * @param string      $task
     * @param int|null    $processExitCode
     * @param string|null $message
     * @param int         $deployedUserId
     * @return void
     */
    public function saveDeployment(int $projectId, int $deploymentId, string $task, ?int $processExitCode, ?string $message, int $deployedUserId): void
    {
        DB::transaction(function () use ($projectId, $deploymentId, $task, $processExitCode, $message, $deployedUserId) {
            $deployment = new Deployment(
                new ProjectId($projectId),
                new DeploymentId($deploymentId),
                new Task($task),
                Status::fromProcessExitCode($processExitCode),
                $message,
                new UserId($deployedUserId),
                null,
                null
            );
            $this->deploymentRepository->save($deployment);
        });
    }

    /**
     * Remove old deployments.
     *
     * @param int                $projectId
     * @param \DateTimeImmutable $currentDate
     * @return void
     */
    public function removeOldDeployments(int $projectId, DateTimeImmutable $currentDate): void
    {
        DB::transaction(function () use ($projectId, $currentDate) {
            $spec = new OldDeploymentSpecification(
                $this->projectService->getProjectById($projectId),
                $currentDate
            );
            $oldDeployments = $this->deploymentRepository->satisfyingDeployments($spec);
            if (!empty($oldDeployments)) {
                $this->deploymentRepository->removeAll($oldDeployments);
            }
        });
    }
}
