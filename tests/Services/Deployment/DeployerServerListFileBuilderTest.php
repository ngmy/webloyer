<?php

use App\Services\Deployment\DeployerServerListFileBuilder;

use Tests\Helpers\Factory;

class DeployerServerListFileBuilderTest extends TestCase
{
    public function test_Should_BuildDeployerServerListFile()
    {
        Storage::shouldReceive('delete')
            ->once()
            ->andReturn(1);

        Storage::shouldReceive('put')
            ->once()
            ->andReturn(1);

        $serverListFileBuilder = new DeployerServerListFileBuilder(
            new App\Models\Server
        );
        $result = $serverListFileBuilder
            ->pathInfo()
            ->put()
            ->getResult();

        $this->assertStringMatchesFormat('server_%x.yml', $result->getBaseName());
        $this->assertStringMatchesFormat(storage_path("app/server_%x.yml"), $result->getFullPath());
    }
}
