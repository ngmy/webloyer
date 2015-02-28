<?php namespace App\Repositories\Project;

use Illuminate\Database\Eloquent\Model;

class EloquentProject implements ProjectInterface {

	protected $project;

	/**
	 * Create a new repository instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $project
	 * @return void
	 */
	public function __construct(Model $project)
	{
		$this->project = $project;
	}

	/**
	 * Get a project by id.
	 *
	 * @param int $id Project id
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function byId($id)
	{
		return $this->project->find($id);
	}

	/**
	 * Get paginated projects.
	 *
	 * @param int $page  Page number
	 * @param int $limit Number of projects per page
	 * @return \Illuminate\Pagination\LengthAwarePaginator
	 */
	public function byPage($page = 1, $limit = 10)
	{
		$projects = $this->project->orderBy('name')
			->skip($limit * ($page - 1))
			->take($limit)
			->paginate($limit);

		return $projects;
	}

	/**
	 * Create a new project.
	 *
	 * @param array $data Data to create a project
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function create(array $data)
	{
		$project = $this->project->create($data);

		return $project;
	}

	/**
	 * Update an existing project.
	 *
	 * @param array $data Data to update a project
	 * @return boolean
	 */
	public function update(array $data)
	{
		$project = $this->project->find($data['id']);

		$project->update($data);

		return true;
	}

	/**
	 * Delete an existing project.
	 *
	 * @param int $id Project id
	 * @return boolean
	 */
	public function delete($id)
	{
		$project = $this->project->find($id);

		$project->delete();

		return true;
	}

}
