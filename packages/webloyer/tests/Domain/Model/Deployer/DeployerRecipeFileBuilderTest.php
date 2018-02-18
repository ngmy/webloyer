<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployer;

use Ngmy\Webloyer\Common\Port\Adapter\Persistence\LaravelFilesystem;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileBuilderInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use TestCase;
use Tests\Helpers\MockeryHelper;

class DeployerRecipeFileBuilderTest extends TestCase
{
    use MockeryHelper;

    private $fs;

    private $deployerFile;

    private $recipe;

    public function setUp()
    {
        parent::setUp();

        $this->fs = $this->partialMock(new LaravelFilesystem($this->app['files']));
        $this->deployerFile = $this->partialMock(DeployerFile::class);

        $this->recipe = $this->mock(Recipe::class);
    }

    public function test_Should_BuildDeployerRecipeFile()
    {
        $expectedBaseNamePattern = '|recipe_[a-zA-Z0-9]{32}.php|';
        $expectedFullPathPattern = '|'. storage_path('app/recipe_[a-zA-Z0-9]{32}.php') . '|';

        $recipeBody = '';

        $this->recipe
            ->shouldReceive('body')
            ->withNoArgs()
            ->andReturn($recipeBody)
            ->once();

        $this->fs
            ->shouldReceive('delete')
            ->with($expectedFullPathPattern)
            ->once();
        $this->fs
            ->shouldReceive('put')
            ->with($expectedFullPathPattern, $recipeBody)
            ->once();

        $recipeFileBuilder = new DeployerRecipeFileBuilder(
            $this->fs,
            $this->deployerFile
        );
        $actualResult = $recipeFileBuilder
            ->setRecipe($this->recipe)
            ->pathInfo()
            ->put()
            ->getResult();

        $this->assertRegExp($expectedBaseNamePattern, $actualResult->getBaseName());
        $this->assertRegExp($expectedFullPathPattern, $actualResult->getFullPath());
    }
}
