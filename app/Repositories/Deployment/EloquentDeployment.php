<?php namespace App\Repositories\Deployment;

use Illuminate\Database\Eloquent\Model;

class EloquentDeployment implements DeploymentInterface {

	protected $deployment;

	/**
	 * Create a new repository instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $deployment
	 * @return void
	 */
	public function __construct(Model $deployment)
	{
		$this->deployment = $deployment;
	}

	/**
	 * Get a deployment by id.
	 *
	 * @param int $id Deployment id
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function byId($id)
	{
		return $this->deployment->find($id);
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
		return $this->deployment
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
		$deployments = $this->deployment
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
		$deployment = $this->deployment->create($data);

		return $deployment;
	}

	/**
	 * Update an existing deployment.
	 *
	 * @param array $data Data to update a deployment
	 * @return boolean
	 */
	public function update(array $data)
	{
		$deployment = $this->deployment->find($data['id']);

		$deployment->update($data);

		return true;
	}

}
