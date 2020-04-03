<?php

namespace App\Services\Deployment;

use App\Services\Deployment\DeployerFile;
use App\Services\Filesystem\FilesystemInterface;
use Illuminate\Database\Eloquent\Model;

class DeployerRecipeFileBuilder implements DeployerFileBuilderInterface
{
    protected $fs;

    protected $deployerFile;

    protected $recipe;

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
     * Set a recipe file path info.
     *
     * @return \App\Services\Deployment\DeployerRecipeFileBuilder $this
     */
    public function pathInfo()
    {
        $id = md5(uniqid(rand(), true));

        $baseName = "recipe_$id.php";
        $fullPath = storage_path("app/$baseName");

        $this->deployerFile->setBaseName($baseName);
        $this->deployerFile->setFullPath($fullPath);

        return $this;
    }

    /**
     * Put a recipe file.
     *
     * @return \App\Services\Deployment\DeployerRecipeFileBuilder $this
     */
    public function put()
    {
        $fullPath = $this->deployerFile->getFullPath();
        $contents = $this->recipe->body;

        $this->fs->put($fullPath, $contents);

        return $this;
    }

    /**
     * Get a recipe file instance.
     *
     * @return \App\Services\Deployment\DeployerFile
     */
    public function getResult()
    {
        return $this->deployerFile;
    }

    /**
     * Set a recipe model instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $recipe
     * @return \App\Services\Deployment\DeployerRecipeFileBuilder $this
     */
    public function setRecipe(Model $recipe)
    {
        $this->recipe = $recipe;

        return $this;
    }
}
