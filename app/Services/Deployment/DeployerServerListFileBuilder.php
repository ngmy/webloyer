<?php namespace App\Services\Deployment;

use App\Repositories\Server\ServerInterface;

use Storage;

use Illuminate\Database\Eloquent\Model;

class DeployerServerListFileBuilder {

	protected $serverRepository;

	protected $deployment;

	protected $project;

	public function __construct(ServerInterface $serverRepository)
	{
		$this->serverRepository = $serverRepository;
	}

	public function __destruct()
	{
		$file = $this->getFilename();

		Storage::delete($file);
	}

	/**
	 * Get a server list file path.
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
	 * @return \App\Services\ServerList\DeployerServerListFileBuilder $this
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
	 * @return \App\Services\ServerList\DeployerServerListFileBuilder $this
	 */
	public function setProject(Model $project)
	{
		$this->project = $project;

		return $this;
	}

	/**
	 * Build a server list file.
	 *
	 * @return \App\Services\ServerList\DeployerServerListFileBuilder $this
	 */
	public function build()
	{
		$server = $this->serverRepository->byId($this->project->server_id);

		$contents = $server->body;

		$file = $this->getFilename();

		Storage::put($file, $contents);

		return $this;
	}

	/**
	 * Get a server list file name.
	 *
	 * @return string
	 */
	protected function getFilename()
	{
		$filename = "servers_{$this->deployment->project_id}_{$this->deployment->number}.yml";

		return $filename;
	}

}
