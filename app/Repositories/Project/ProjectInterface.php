<?php namespace App\Repositories\Project;

interface ProjectInterface {

	/**
	 * Get a project by id.
	 *
	 * @param int $id Project id
	 * @return mixed
	 */
	public function byId($id);

	/**
	 * Get paginated projects.
	 *
	 * @param int $page  Page number
	 * @param int $limit Number of projects per page
	 * @return mixed
	 */
	public function byPage($page = 1, $limit = 10);

	/**
	 * Create a new project.
	 *
	 * @param array $data Data to create a project
	 * @return mixed
	 */
	public function create(array $data);

	/**
	 * Update an existing project.
	 *
	 * @param array $data Data to update a project
	 * @return mixed
	 */
	public function update(array $data);

	/**
	 * Delete an existing project.
	 *
	 * @param int $id Project id
	 * @return mixed
	 */
	public function delete($id);

}
