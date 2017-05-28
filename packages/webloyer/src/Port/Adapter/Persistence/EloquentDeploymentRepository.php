<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\Common\QueryObject\QueryObject;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Status;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Task;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\AbstractDeploymentSpecification;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\Deployment as EloquentDeployment;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Persistence\Eloquent\MaxDeployment as EloquentMaxDeployment;

class EloquentDeploymentRepository implements DeploymentRepositoryInterface
{
    private $eloquentDeployment;

    private $eloquentMaxDeployment;

    public function __construct(EloquentDeployment $eloquentDeployment, EloquentMaxDeployment $eloquentMaxDeployment)
    {
        $this->eloquentDeployment = $eloquentDeployment;
        $this->eloquentMaxDeployment = $eloquentMaxDeployment;
    }

    public function nextIdentity(Project $project)
    {
        $maxDeployment = $this->eloquentMaxDeployment
            ->where('project_id', $project->projectId()->id())
            ->first();

        if (is_null($maxDeployment)) {
            $maxDeployment = new EloquentMaxDeployment();
            $maxDeployment->project_id = $project->projectId()->id();
            $maxDeployment->number = 0;
        }

        $maxDeployment->number += 1;
        $maxDeployment->save();

        $nextDeploymentId = new DeploymentId($maxDeployment->number);

        return $nextDeploymentId;
    }

    public function deployments(QueryObject $queryObject)
    {
        $query = $this->eloquentDeployment->select();

        if (!is_null($queryObject->criteria()->projectId()->id())) {
            $query->where('project_id', $queryObject->criteria()->projectId()->id());
        }

        foreach ($queryObject->orders() as $order) {
            $query->orderBy($order->column(), $order->direction()->value());
        }

        if (!is_null($queryObject->limit())) {
            $query->offset($queryObject->limit()->offset())
                ->limit($queryObject->limit()->limit());
        }

        $deployments = $query
            ->get()
            ->map(function ($eloquentDeployment, $key) {
                return $this->toEntity($eloquentDeployment);
            });

        if (!is_null($queryObject->pagination())) {
            $deployments = new LengthAwarePaginator(
                $deployments->slice($queryObject->pagination()->limit() * ($queryObject->pagination()->page() - 1), $queryObject->pagination()->limit()),
                $deployments->count(),
                $queryObject->pagination()->limit(),
                $queryObject->pagination()->page(),
                [
                    'path' => Paginator::resolveCurrentPath(),
                ]
            );
        }

        return $deployments;
    }

    public function satisfyingDeployments(AbstractDeploymentSpecification $spec)
    {
        return $spec->satisfyingElementsFrom($this);
    }

    public function deploymentOfId(Project $project, DeploymentId $deploymentId)
    {
        $eloquentDeployment = $this->eloquentDeployment
            ->where('project_id', $project->projectId()->id())
            ->where('number', $deploymentId->id())
            ->first();

        if (is_null($eloquentDeployment)) {
            return null;
        }

        $deployment = $this->toEntity($eloquentDeployment);

        return $deployment;
    }

    public function remove(Deployment $deployment)
    {
        $eloquentDeployment = $this->toEloquent($deployment);

        $eloquentDeployment->delete();

        return true;
    }

    public function removeAll(array $deployments)
    {
        if (empty($deployments)) {
            return true;
        }

        foreach ($deployments as $deployment) {
            $eloquentDeployments[] = $this->toEloquent($deployment);
        }

        $eloquentDeploymentIds = array_column($eloquentDeployments, 'id');

        $this->eloquentDeployment->whereIn('id', $eloquentDeploymentIds)->delete();

        return true;
    }

    public function save(Deployment $deployment)
    {
        $eloquentDeployment = $this->toEloquent($deployment);

        $eloquentDeployment->save();

        $deployment = $this->toEntity($eloquentDeployment);

        return $deployment;
    }

    public function toEntity(EloquentDeployment $eloquentDeployment)
    {
        $projectId = new ProjectId($eloquentDeployment->project_id);
        $deploymentId = new DeploymentId($eloquentDeployment->number);
        $task = new Task($eloquentDeployment->task);
        $status = new Status($eloquentDeployment->status);
        $message = $eloquentDeployment->message;
        $deployedUserId = new UserId($eloquentDeployment->user_id);
        $createdAt = $eloquentDeployment->created_at;
        $updatedAt = $eloquentDeployment->updated_at;

        $deployment = new Deployment(
            $projectId,
            $deploymentId,
            $task,
            $status,
            $message,
            $deployedUserId,
            $createdAt,
            $updatedAt
        );

        return $deployment;
    }

    private function toEloquent(Deployment $deployment)
    {
        $primaryKey = $deployment->deploymentId()->id();

        $eloquentDeployment = $this->eloquentDeployment
            ->where('project_id', $deployment->projectId()->id())
            ->where('number', $deployment->deploymentId()->id())
            ->first();

        if (is_null($eloquentDeployment)) {
            $eloquentDeployment = new EloquentDeployment();
            $eloquentDeployment->project_id = $deployment->projectId()->id();
            $eloquentDeployment->number = $deployment->deploymentId()->id();
        }

        $eloquentDeployment->task = $deployment->task()->value();
        $eloquentDeployment->status = $deployment->status()->value();
        $eloquentDeployment->message = $deployment->message();
        $eloquentDeployment->user_id = $deployment->deployedUserId()->id();

        return $eloquentDeployment;
    }
}
