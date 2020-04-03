<?php

namespace Tests\Unit\app\Services\Deployment;

use App\Services\Deployment\StorageDeployCommander;

class StorageDeployCommanderTest extends TestCase
{
    public function test_Should_ReturnTrue_When_DeployCommandSucceeds()
    {
        Storage::shouldReceive('put')
            ->once()
            ->andReturn(1);

        $deployCommander = new StorageDeployCommander;
        $result = $deployCommander->deploy(new App\Models\Deployment);

        $this->assertTrue($result, 'Expected deploy command to succeed.');
    }

    public function test_Should_ReturnFalse_When_DeployCommandFails()
    {
        Storage::shouldReceive('put')
            ->once()
            ->andReturn(0);

        $deployCommander = new StorageDeployCommander;
        $result = $deployCommander->deploy(new App\Models\Deployment);

        $this->assertFalse($result, 'Expected deploy command to fail.');
    }

    public function test_Should_ReturnTrue_When_RollbackCommandSucceeds()
    {
        Storage::shouldReceive('put')
            ->once()
            ->andReturn(1);

        $deployCommander = new StorageDeployCommander;
        $result = $deployCommander->rollback(new App\Models\Deployment);

        $this->assertTrue($result, 'Expected rollback command to succeed.');
    }

    public function test_Should_ReturnFalse_When_RollbackCommandFails()
    {
        Storage::shouldReceive('put')
            ->once()
            ->andReturn(0);

        $deployCommander = new StorageDeployCommander;
        $result = $deployCommander->rollback(new App\Models\Deployment);

        $this->assertFalse($result, 'Expected rollback command to fail.');
    }
}
