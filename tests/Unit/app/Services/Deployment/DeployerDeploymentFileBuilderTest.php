<?php

namespace Tests\Unit\app\Services\Deployment;


use App\Models\Project;
use App\Services\Deployment\DeployerDeploymentFileBuilder;
use App\Services\Deployment\DeployerFile;
use App\Services\Filesystem\FilesystemInterface;
use App\Services\Filesystem\LaravelFilesystem;
use Tests\TestCase;

class DeployerDeploymentFileBuilderTest extends TestCase
{
    protected $mockProjectModel;

    protected $mockFilesystem;

    protected $mockRecipeFile;

    protected $mockServerListFile;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockProjectModel = $this->partialMock(Project::class);
        $this->mockFilesystem = $this->mock(FilesystemInterface::class);
        $this->mockRecipeFile = $this->mock(DeployerFile::class);
        $this->mockServerListFile = $this->mock(DeployerFile::class);
    }

    public function test_Should_BuildDeployerDeploymentFile()
    {
        $this->mockFilesystem
            ->shouldReceive('delete')
            ->once();
        $this->mockFilesystem
            ->shouldReceive('put')
            ->once();

        $mockRecipeFile = $this->mockRecipeFile
            ->shouldReceive('getFullPath')
            ->once()
            ->mock();
        $mockRecipeFiles = [$mockRecipeFile];

        $mockServerListFile = $this->mockServerListFile
            ->shouldReceive('getFullPath')
            ->once()
            ->mock();

        $deploymentFileBuilder = new DeployerDeploymentFileBuilder(
            $this->mockFilesystem,
            new DeployerFile
        );
        $deploymentFileBuilder->setProject($this->mockProjectModel)
            ->setServerListFile($mockServerListFile)
            ->setRecipeFile($mockRecipeFiles);
        $result = $deploymentFileBuilder
            ->pathInfo()
            ->put()
            ->getResult();

        $this->assertStringMatchesFormat('deploy_%x.php', $result->getBaseName());
        $this->assertStringMatchesFormat(storage_path("app/deploy_%x.php"), $result->getFullPath());
    }
}
