<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployer;

use Ngmy\Webloyer\Common\Filesystem\FilesystemInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileBuilderInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;

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
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerRecipeFileBuilder $this
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
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerRecipeFileBuilder $this
     */
    public function put()
    {
        $fullPath = $this->deployerFile->getFullPath();
        $contents = $this->recipe->body();

        $this->fs->put($fullPath, $contents);

        return $this;
    }

    /**
     * Get a recipe file instance.
     *
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile
     */
    public function getResult()
    {
        return $this->deployerFile;
    }

    /**
     * Set a recipe model instance.
     *
     * @param \Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe $recipe
     * @return \Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerRecipeFileBuilder $this
     */
    public function setRecipe(Recipe $recipe)
    {
        $this->recipe = $recipe;

        return $this;
    }
}
