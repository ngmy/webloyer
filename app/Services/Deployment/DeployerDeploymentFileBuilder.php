<?php namespace App\Services\Deployment;

use Storage;

use Illuminate\Database\Eloquent\Model;

class DeployerDeploymentFileBuilder implements DeployerFileBuilderInterface {

	protected $deployerFile;

	protected $project;

	protected $serverListFile;

	protected $recipeFile;

	public function __construct(Model $project, DeployerFile $serverListFile, $recipeFile)
	{
		$this->deployerFile   = new DeployerFile;
		$this->project        = $project;
		$this->serverListFile = $serverListFile;
		$this->recipeFile     = $recipeFile;
	}

	public function __destruct()
	{
		Storage::delete($this->deployerFile->getBaseName());
	}

	/**
	 * Set a deployment file path info.
	 *
	 * @return \App\Services\Deployment\DeployerDeploymentFileBuilder $this
	 */
	public function pathInfo()
	{
		$id = md5(uniqid(rand(), true));

		$baseName = "deploy_$id.php";
		$fullPath = storage_path("app/$baseName");

		$this->deployerFile->setBaseName($baseName);
		$this->deployerFile->setFullPath($fullPath);

		return $this;
	}

	/**
	 * Put a deployment file.
	 *
	 * @return \App\Services\Deployment\DeployerDeploymentFileBuilder $this
	 */
	public function put()
	{
		$baseName = $this->deployerFile->getBaseName();
		$contents[] = '<?php';

		// Include recipe files
		foreach ($this->recipeFile as $recipeFile) {
			$contents[] = "require '{$recipeFile->getFullPath()}';";
		}

		// Set a repository
		$contents[] = "set('repository', '{$this->project->repository}');";

		// Load a server list file
		$contents[] = "serverList('{$this->serverListFile->getFullPath()}');";

		Storage::put($baseName, implode(PHP_EOL, $contents));

		return $this;
	}

	/**
	 * Get a deployment file instance.
	 *
	 * @return \App\Services\Deployment\DeployerFile
	 */
	public function getResult()
	{
		return $this->deployerFile;
	}

}
