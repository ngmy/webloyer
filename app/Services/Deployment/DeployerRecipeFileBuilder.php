<?php
declare(strict_types=1);

namespace App\Services\Deployment;

use App\Services\Filesystem\FilesystemInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DeployerRecipeFileBuilder
 * @package App\Services\Deployment
 */
class DeployerRecipeFileBuilder implements DeployerFileBuilderInterface
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
    protected ?Model $recipe;

    /**
     * @var bool
     */
    protected bool $deployerFileInitialized = false;

    /**
     * DeployerRecipeFileBuilder constructor.
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
     * Set a recipe file path info.
     *
     * @return DeployerRecipeFileBuilder $this
     */
    public function pathInfo()
    {
        $id = md5(uniqid((string)rand(), true));

        $baseName = "recipe_$id.php";
        $fullPath = storage_path("app/$baseName");

        $this->deployerFile->setBaseName($baseName);
        $this->deployerFile->setFullPath($fullPath);

        return $this;
    }

    /**
     * Put a recipe file.
     *
     * @return DeployerRecipeFileBuilder $this
     */
    public function put()
    {
        $fullPath = $this->deployerFile->getFullPath();
        $contents = $this->recipe->body;
        $this->fs->put($fullPath, $contents);
        $this->deployerFileInitialized = true;
        return $this;
    }

    /**
     * Get a recipe file instance.
     *
     * @return DeployerFile
     */
    public function getResult()
    {
        return $this->deployerFile;
    }

    /**
     * Set a recipe model instance.
     *
     * @param Model $recipe
     * @return DeployerRecipeFileBuilder $this
     */
    public function setRecipe(Model $recipe)
    {
        $this->recipe = $recipe;

        return $this;
    }
}
