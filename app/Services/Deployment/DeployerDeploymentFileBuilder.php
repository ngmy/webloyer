<?php

namespace App\Services\Deployment;

use App\Services\Deployment\DeployerFile;
use App\Services\Filesystem\FilesystemInterface;
use Illuminate\Database\Eloquent\Model;

class DeployerDeploymentFileBuilder implements DeployerFileBuilderInterface
{
    protected $fs;

    protected $deployerFile;

    protected $project;

    protected $serverListFile;

    protected $recipeFile;

    public function __construct(FilesystemInterface $fs, DeployerFile $deployerFile)
    {
        $this->fs           = $fs;
        $this->deployerFile = $deployerFile;
    }

    public function __destruct()
    {
        $this->fs->delete($this->deployerFile->getFullPath());
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
        $fullPath = $this->deployerFile->getFullPath();
        $contents[] = '<?php';

        // Include recipe files
        foreach ($this->recipeFile as $recipeFile) {
            $contents[] = "require '{$recipeFile->getFullPath()}';";
        }

        // Set a repository
        $contents[] = "set('repository', '{$this->project->repository}');";

        // Load a server list file
        $contents[] = "serverList('{$this->serverListFile->getFullPath()}');";

        $this->fs->put($fullPath, implode(PHP_EOL, $contents));

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
     * Set a server list file instance.
     *
     * @param \App\Services\Deployment\DeployerFile $serverListFile
     * @return \App\Services\Deployment\DeployerDeploymentFileBuilder $this
     */
    public function setServerListFile(DeployerFile $serverListFile)
    {
        $this->serverListFile = $serverListFile;

        return $this;
    }

    /**
     * Set recipe file instances.
     *
     * @param array $recipeFile
     * @return \App\Services\Deployment\DeployerDeploymentFileBuilder $this
     */
    public function setRecipeFile(array $recipeFile)
    {
        $this->recipeFile = $recipeFile;

        return $this;
    }
}
