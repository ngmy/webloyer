<?php

namespace Ngmy\Webloyer\Webloyer\Application\Deployment;

use DateTimeImmutable;
use DB;
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

    public function __construct(ProjectService $projectService, DeploymentRepositoryInterface $deploymentRepository)
    {
        $this->projectService = $projectService;
        $this->deploymentRepository = $deploymentRepository;
    }

    public function getDeploymentsOfProjectAndPage($projectId, $page = 1, $perPage = 10)
    {
        $criteria = new DeploymentCriteria($projectId);
        $order = new Order('deployments.created_at', Direction::desc());
        $pagination = new Pagination($page, $perPage);
        $queryObject = new QueryObject();
        $queryObject->setCriteria($criteria)
            ->addOrder($order)
            ->setPagination($pagination);
        return $this->deploymentRepository->deployments($queryObject);
    }

    public function getLastDeploymentOfProject($projectId)
    {
        $criteria = new DeploymentCriteria($projectId);
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

    public function getAllDeployments()
    {
        return $this->deploymentRepository->allDeployments();
    }

    public function getDeploymentOfId($projectId, $deploymentId)
    {
        return $this->deploymentRepository->deploymentOfId(
            $this->projectService->getProjectOfId($projectId),
            new DeploymentId($deploymentId)
        );
    }

    public function getNextDeploymentIdOfProject($projectId)
    {
        return $this->deploymentRepository->nextIdentity(
            $this->projectService->getProjectOfId($projectId)
        );
    }

    public function saveDeployment($projectId, $deploymentId, $task, $processExitCode, $message, $deployedUserId)
    {
        $deployment = DB::transaction(function () use ($projectId, $deploymentId, $task, $processExitCode, $message, $deployedUserId) {
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
            return $this->deploymentRepository->save($deployment);
        });

        return $deployment;
    }

    public function removeOldDeploymentsOfProject($projectId, DateTimeImmutable $currentDate)
    {
        DB::transaction(function () use ($projectId, $currentDate) {
            $spec = new OldDeploymentSpecification(
                $this->projectService->getProjectOfId($projectId),
                $currentDate
            );
            $oldDeployments = $this->deploymentRepository->satisfyingDeployments($spec);
            if (!empty($oldDeployments)) {
                $this->deploymentRepository->removeAll($oldDeployments);
            }
        });
        return true;
    }
}
