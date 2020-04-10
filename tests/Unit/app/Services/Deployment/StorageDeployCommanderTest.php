<?php

namespace Tests\Unit\app\Services\Deployment;

use App\Models\Deployment;
use App\Services\Deployment\StorageDeployCommander;
use Storage;
use Tests\TestCase;

class StorageDeployCommanderTest extends TestCase
{
    public function testShouldReturnTrueWhenDeployCommandSucceeds()
    {
        Storage::shouldReceive('put')
            ->once()
            ->andReturn(1);

        $deployCommander = new StorageDeployCommander();
        $result = $deployCommander->deploy(new Deployment());

        $this->assertTrue($result, 'Expected deploy command to succeed.');
    }

    public function testShouldReturnFalseWhenDeployCommandFails()
    {
        Storage::shouldReceive('put')
            ->once()
            ->andReturn(0);

        $deployCommander = new StorageDeployCommander();
        $result = $deployCommander->deploy(new Deployment());

        $this->assertFalse($result, 'Expected deploy command to fail.');
    }

    public function testShouldReturnTrueWhenRollbackCommandSucceeds()
    {
        Storage::shouldReceive('put')
            ->once()
            ->andReturn(1);

        $deployCommander = new StorageDeployCommander();
        $result = $deployCommander->rollback(new Deployment());

        $this->assertTrue($result, 'Expected rollback command to succeed.');
    }

    public function testShouldReturnFalseWhenRollbackCommandFails()
    {
        Storage::shouldReceive('put')
            ->once()
            ->andReturn(0);

        $deployCommander = new StorageDeployCommander();
        $result = $deployCommander->rollback(new Deployment());

        $this->assertFalse($result, 'Expected rollback command to fail.');
    }
}
