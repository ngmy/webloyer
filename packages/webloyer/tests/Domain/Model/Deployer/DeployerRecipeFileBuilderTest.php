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

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_BuildDeployerRecipeFile()
    {
        $expectedRecipeFileBaseNamePattern = '|recipe_[a-zA-Z0-9]{32}.php|';
        $expectedRecipeFileFullPathPattern = '|'. storage_path('app/recipe_[a-zA-Z0-9]{32}.php') . '|';

        $recipeBody = '';
        $expectedRecipeFileContents = '';

        $this->recipe
            ->shouldReceive('body')
            ->withNoArgs()
            ->andReturn($recipeBody)
            ->once();

        $this->fs
            ->shouldReceive('delete')
            ->with($expectedRecipeFileFullPathPattern)
            ->once();
        $this->fs
            ->shouldReceive('put')
            ->with($expectedRecipeFileFullPathPattern, $expectedRecipeFileContents)
            ->once();

        $deployerRecipeFileBuilder = new DeployerRecipeFileBuilder(
            $this->fs,
            $this->deployerFile
        );
        $actualResult = $deployerRecipeFileBuilder
            ->setRecipe($this->recipe)
            ->pathInfo()
            ->put()
            ->getResult();

        $this->assertRegExp($expectedRecipeFileBaseNamePattern, $actualResult->getBaseName());
        $this->assertRegExp($expectedRecipeFileFullPathPattern, $actualResult->getFullPath());
    }
}
