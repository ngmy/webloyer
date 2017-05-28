<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployer;

use Ngmy\Webloyer\Common\Filesystem\FilesystemInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileBuilderInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;

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
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerDeploymentFileBuilder $this
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
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerDeploymentFileBuilder $this
     */
    public function put()
    {
        $fullPath = $this->deployerFile->getFullPath();
        $contents[] = '<?php';

        // Declare a namespace
        $contents[] = 'namespace Deployer;';

        // Include recipe files
        foreach ($this->recipeFile as $recipeFile) {
            $contents[] = "require '{$recipeFile->getFullPath()}';";
        }

        // Set a repository
        $contents[] = "set('repository', '{$this->project->repositoryUrl()}');";

        // Load a server list file
        $contents[] = "serverList('{$this->serverListFile->getFullPath()}');";

        $this->fs->put($fullPath, implode(PHP_EOL, $contents));

        return $this;
    }

    /**
     * Get a deployment file instance.
     *
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile
     */
    public function getResult()
    {
        return $this->deployerFile;
    }

    /**
     * Set a project model instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project $project
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerDeploymentFileBuilder $this
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Set a server list file instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile $serverListFile
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerDeploymentFileBuilder $this
     */
    public function setServerListFile(DeployerFile $serverListFile)
    {
        $this->serverListFile = $serverListFile;

        return $this;
    }

    /**
     * Set recipe file instances.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile[] $recipeFile
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerDeploymentFileBuilder $this
     */
    public function setRecipeFile(array $recipeFile)
    {
        $this->recipeFile = $recipeFile;

        return $this;
    }
}
