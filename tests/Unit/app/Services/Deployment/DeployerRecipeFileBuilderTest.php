<?php

namespace Tests\Unit\app\Services\Deployment;

use App\Models\Recipe;
use App\Services\Deployment\DeployerRecipeFileBuilder;
use App\Services\Deployment\DeployerFile;
use App\Services\Filesystem\FilesystemInterface;
use App\Services\Filesystem\LaravelFilesystem;
use Tests\TestCase;

class DeployerRecipeFileBuilderTest extends TestCase
{
    protected $mockRecipeModel;

    protected $mockFilesystem;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockRecipeModel = $this->partialMock(Recipe::class);
        $this->mockFilesystem = $this->mock(FilesystemInterface::class);
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
