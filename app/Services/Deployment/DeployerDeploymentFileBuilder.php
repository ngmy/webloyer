<?php namespace App\Services\Deployment;

use App\Repositories\Recipe\RecipeInterface;

use Storage;

use Illuminate\Database\Eloquent\Model;

class DeployerDeploymentFileBuilder {

	protected $recipeRepository;

	protected $deployment;

	protected $project;

	public function __construct(RecipeInterface $recipeRepository)
	{
		$this->recipeRepository = $recipeRepository;
	}

	public function __destruct()
	{
		$file = $this->getFilename();

		Storage::delete($file);
	}

	/**
	 * Get a deployment file path.
	 *
	 * @return string
	 */
	public function getFilePath()
	{
		$filename = $this->getFilename();

		return storage_path("app/{$filename}");
	}

	/**
	 * Set a deployment model instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $deployment
	 * @return \App\Services\Deployment\DeployerDeploymentFileBuilder $this
	 */
	public function setDeployment(Model $deployment)
	{
		$this->deployment = $deployment;

		return $this;
	}

	/**
	 * Set a project model instance.
	 *
	 * @param \Illuminate\Database\Eloquent\Model $project
	 * @return \App\Services\Deployment\DeployerDeploymentFileBuilder $this
	 */
	public function setProject(Model $project)
	{
		$this->project = $project;

		return $this;
	}

	/**
	 * Build a deployment file.
	 *
	 * @return \App\Services\Deployment\DeployerDeploymentFileBuilder $this
	 */
	public function build()
	{
		$recipe = $this->recipeRepository->byId($this->project->recipe_id);

		$contents   = $recipe->body;
		$repository = $this->project->repository;
		$servers    = $this->project->servers;

		$contents = preg_replace('/\{\{\s*repository\s*\}\}/', $repository, $contents);
		$contents = preg_replace('/\{\{\s*servers\s*\}\}/', $servers, $contents);

		$file = $this->getFilename();

		Storage::put($file, $contents);

		return $this;
	}

	/**
	 * Get a deployment file name.
	 *
	 * @return string
	 */
	protected function getFilename()
	{
		$filename = "deploy_{$this->deployment->project_id}_{$this->deployment->number}.php";

		return $filename;
	}

}
