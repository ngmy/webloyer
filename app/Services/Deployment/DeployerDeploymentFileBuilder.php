<?php
declare(strict_types=1);

namespace App\Services\Deployment;

use App\Services\Filesystem\FilesystemInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DeployerDeploymentFileBuilder
 * @package App\Services\Deployment
 */
class DeployerDeploymentFileBuilder implements DeployerFileBuilderInterface
{
    /**
     * @var FilesystemInterface
     */
    protected FilesystemInterface $fs;

    /**
     * @var DeployerFile
     */
    protected DeployerFile $deployerFile;

    /**
     * @var null|Model
     */
    protected ?Model $project;

    /**
     * @var null|DeployerFile
     */
    protected ?DeployerFile $serverListFile;

    /**
     * @var null|array
     */
    protected ?array $recipeFile;

    /**
     * @var bool
     */
    protected bool $deployerFileInitialized = false;

    /**
     * DeployerDeploymentFileBuilder constructor.
     * @param FilesystemInterface $fs
     * @param DeployerFile $deployerFile
     */
    public function __construct(FilesystemInterface $fs, DeployerFile $deployerFile)
    {
        $this->fs = $fs;
        $this->deployerFile = $deployerFile;
    }

    public function __destruct()
    {
        $this->fs->delete($this->deployerFile->getFullPath());
    }

    /**
     * Set a deployment file path info.
     *
     * @return DeployerDeploymentFileBuilder $this
     */
    public function pathInfo()
    {
        $id = md5(uniqid((string)rand(), true));

        $baseName = "deploy_$id.php";
        $fullPath = storage_path("app/$baseName");

        $this->deployerFile->setBaseName($baseName);
        $this->deployerFile->setFullPath($fullPath);

        return $this;
    }

    /**
     * Put a deployment file.
     *
     * @return DeployerDeploymentFileBuilder $this
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
        $contents[] = "set('repository', '{$this->project->repository}');";

        $sshPassword = getenv('SSH_PASSWORD');
        $sshPath = getenv('SSH_PATH');
        $contents[] = "putenv('SSH_PASSWORD={$sshPassword}');";
        $contents[] = "putenv('SSH_PATH={$sshPath}');";

        // Load a server list file
        $contents[] = "import('{$this->serverListFile->getFullPath()}');";
        $this->fs->put($fullPath, implode(PHP_EOL, $contents));
        $this->deployerFileInitialized = true;
        return $this;
    }

    /**
     * Get a deployment file instance.
     *
     * @return DeployerFile
     */
    public function getResult()
    {
        return $this->deployerFile;
    }

    /**
     * Set a project model instance.
     *
     * @param Model $project
     * @return DeployerDeploymentFileBuilder $this
     */
    public function setProject(Model $project)
    {
        $this->project = $project;
        return $this;
    }

    /**
     * Set a server list file instance.
     *
     * @param DeployerFile $serverListFile
     * @return DeployerDeploymentFileBuilder $this
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
     * @return DeployerDeploymentFileBuilder $this
     */
    public function setRecipeFile(array $recipeFile)
    {
        $this->recipeFile = $recipeFile;
        return $this;
    }
}
