<?php namespace App\Repositories\Project;

use Illuminate\Database\Eloquent\Model;

use DB;

class EloquentProject implements ProjectInterface {

	protected $project;

	protected $maxDeployment;

	protected $projectRecipe;

	/**
	 * Create a new repository instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $project
	 * @param \Illuminate\Database\Eloquent\Model $maxDeployment
	 * @param \Illuminate\Database\Eloquent\Model $projectRecipe
	 * @return void
	 */
	public function __construct(Model $project, Model $maxDeployment, Model $projectRecipe)
	{
		$this->project = $project;
		$this->maxDeployment = $maxDeployment;
		$this->projectRecipe = $projectRecipe;
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
		$projects = $this->project->with(['deployments' => function ($query)
		{
			$query->orderBy('number', 'desc');
		}])->orderBy('name')
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
		$project = DB::transaction(function () use ($data)
		{
			// Insert data to `project` table
			$project = $this->project->create($data);

			// Insert data to `max_deployment` table
			$this->maxDeployment->project_id = $project->id;
			$this->maxDeployment->save();

			// Insert data to `project_recipe` table
			foreach ($data['recipe_id'] as $i => $recipeId) {
				$this->projectRecipe->create([
					'project_id'   => $project->id,
					'recipe_id'    => $recipeId,
					'recipe_order' => $i + 1,
				]);
			}

			return $project;
		});

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
		$project = DB::transaction(function () use ($data)
		{
			// Update data in `project` table
			$project = $this->project->find($data['id']);

			$project->update($data);

			// Replace data in `project_recipe` table
			$this->projectRecipe->where('project_id', $project->id)->delete();

			foreach ($data['recipe_id'] as $i => $recipeId) {
				$this->projectRecipe->create([
					'project_id'   => $project->id,
					'recipe_id'    => $recipeId,
					'recipe_order' => $i + 1,
				]);
			}
		});

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
