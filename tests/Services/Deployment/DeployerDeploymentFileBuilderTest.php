<?php

use App\Services\Deployment\DeployerDeploymentFileBuilder;
use App\Services\Deployment\DeployerFile;
use App\Services\Filesystem\LaravelFilesystem;

class DeployerDeploymentFileBuilderTest extends TestCase
{
    use Tests\Helpers\MockeryHelper;

    protected $mockProjectModel;

    protected $mockFilesystem;

    protected $mockRecipeFile;

    protected $mockServerListFile;

    public function setUp()
    {
        parent::setUp();

        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
        $this->mockFilesystem = $this->mock('App\Services\Filesystem\FilesystemInterface');
        $this->mockRecipeFile = $this->mock('App\Services\Deployment\DeployerFile');
        $this->mockServerListFile = $this->mock('App\Services\Deployment\DeployerFile');
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
