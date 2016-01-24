<?php

use App\Services\Deployment\DeployerDeploymentFileBuilder;

use Tests\Helpers\Factory;

class DeployerDeploymentFileBuilderTest extends TestCase
{
    public function test_Should_BuildDeployerDeploymentFile()
    {
        Storage::shouldReceive('delete')
            ->once()
            ->andReturn(1);

        Storage::shouldReceive('put')
            ->once()
            ->andReturn(1);

        $deploymentFileBuilder = new DeployerDeploymentFileBuilder(
            new App\Models\Project,
            new App\Services\Deployment\DeployerFile,
            [new App\Services\Deployment\DeployerFile]
        );
        $result = $deploymentFileBuilder
            ->pathInfo()
            ->put()
            ->getResult();

        $this->assertStringMatchesFormat('deploy_%x.php', $result->getBaseName());
        $this->assertStringMatchesFormat(storage_path("app/deploy_%x.php"), $result->getFullPath());
    }
}
