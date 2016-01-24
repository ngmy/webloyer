<?php

namespace App\Repositories\Deployment;

use App\Repositories\AbstractEloquentRepository;
use Illuminate\Database\Eloquent\Model;
use DB;

class EloquentDeployment extends AbstractEloquentRepository implements DeploymentInterface
{
    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $deployment
     * @return void
     */
    public function __construct(Model $deployment)
    {
        $this->model = $deployment;
    }

    /**
     * Get a deployment by project id and number.
     *
     * @param int $projectId Project id
     * @param int $number    Deployment number
     * @return mixed
     */
    public function byProjectIdAndNumber($projectId, $number)
    {
        return $this->model
            ->where('project_id', $projectId)
            ->where('number', $number)
            ->first();
    }

    /**
     * Get deployments by project id.
     *
     * @param int $projectId Project id
     * @param int $page      Page number
     * @param int $limit     Number of deployments per page
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function byProjectId($projectId, $page = 1, $limit = 10)
    {
        $deployments = $this->model
            ->where('project_id', $projectId)
            ->orderBy('deployments.created_at', 'desc')
            ->skip($limit * ($page - 1))
            ->take($limit)
            ->paginate($limit);

        return $deployments;
    }

    /**
     * Create a new deployment.
     *
     * @param array $data Data to create a deployment
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        $deployment = DB::transaction(function () use ($data) {
            $maxDeployment = DB::table('max_deployments')
                ->where('project_id', $data['project_id'])
                ->lockForUpdate()
                ->first();

            $data['number'] = $maxDeployment->number + 1;

            $deployment = $this->model->create($data);

            DB::table('max_deployments')
                ->where('project_id', $data['project_id'])
                ->update(['number' => $data['number']]);

            return $deployment;
        });

        return $deployment;
    }
}
