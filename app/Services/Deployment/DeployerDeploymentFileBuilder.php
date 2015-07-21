<?php namespace App\Services\Deployment;

use App\Repositories\Deployment\DeploymentInterface;
use App\Repositories\Project\ProjectInterface;

use Storage;

use Illuminate\Database\Eloquent\Model;

class DeployerDeploymentFileBuilder {

	protected $deployment;

	protected $project;

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
		$contents = <<<EOF
<?php
require '{$this->project->recipe_path}';

serverList('{$this->project->servers}');

set('repository', '{$this->project->repository}');
EOF;

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
