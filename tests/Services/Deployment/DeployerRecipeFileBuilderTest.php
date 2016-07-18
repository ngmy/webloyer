<?php

use App\Services\Deployment\DeployerRecipeFileBuilder;
use App\Services\Deployment\DeployerFile;
use App\Services\Filesystem\LaravelFilesystem;

class DeployerRecipeFileBuilderTest extends TestCase
{
    use Tests\Helpers\MockeryHelper;

    protected $mockRecipeModel;

    protected $mockFilesystem;

    public function setUp()
    {
        parent::setUp();

        $this->mockRecipeModel = $this->mockPartial('App\Models\Recipe');
        $this->mockFilesystem = $this->mock('App\Services\Filesystem\FilesystemInterface');
    }

    public function test_Should_BuildDeployerRecipeFile()
    {
        $this->mockFilesystem
            ->shouldReceive('delete')
            ->once();
        $this->mockFilesystem
            ->shouldReceive('put')
            ->once();

        $recipeFileBuilder = new DeployerRecipeFileBuilder(
            $this->mockFilesystem,
            new DeployerFile
        );
        $recipeFileBuilder->setRecipe($this->mockRecipeModel);
        $result = $recipeFileBuilder
            ->pathInfo()
            ->put()
            ->getResult();

        $this->assertStringMatchesFormat('recipe_%x.php', $result->getBaseName());
        $this->assertStringMatchesFormat(storage_path("app/recipe_%x.php"), $result->getFullPath());
    }
}
