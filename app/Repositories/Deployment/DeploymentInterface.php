<?php namespace App\Repositories\Deployment;

interface DeploymentInterface {

	/**
	 * Get a deployment by id.
	 *
	 * @param int $id Deployment id
	 * @return mixed
	 */
	public function byId($id);

	/**
	 * Get a deployment by project id and number.
	 *
	 * @param int $projectId Project id
	 * @param int $number    Deployment number
	 * @return mixed
	 */
	public function byProjectIdAndNumber($projectId, $number);

	/**
	 * Get deployments by project id.
	 *
	 * @param int $projectId Project id
	 * @param int $page      Page number
	 * @param int $limit     Number of deployments per page
	 * @return mixed
	 */
	public function byProjectId($projectId, $page = 1, $limit = 10);

	/**
	 * Create a new deployment.
	 *
	 * @param array $data Data to create a deployment
	 * @return mixed
	 */
	public function create(array $data);

	/**
	 * Update an existing deployment.
	 *
	 * @param array $data Data to update a deployment
	 * @return mixed
	 */
	public function update(array $data);

}
