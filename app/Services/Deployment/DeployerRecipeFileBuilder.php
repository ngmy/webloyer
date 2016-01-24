<?php

namespace App\Services\Deployment;

use Storage;

use Illuminate\Database\Eloquent\Model;

class DeployerRecipeFileBuilder implements DeployerFileBuilderInterface
{
    protected $deployerFile;

    protected $recipe;

    public function __construct(Model $recipe)
    {
        $this->deployerFile = new DeployerFile;
        $this->recipe = $recipe;
    }

    public function __destruct()
    {
        Storage::delete($this->deployerFile->getBaseName());
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
        $baseName = $this->deployerFile->getBaseName();
        $contents = $this->recipe->body;

        Storage::put($baseName, $contents);

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
}
