<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Messaging;

use App\Jobs\RunDeployer;
use Illuminate\Contracts\Bus\Dispatcher;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Messaging\QueueDeployerDispatcherService;
use TestCase;
use Tests\Helpers\MockeryHelper;

class QueueDeployerDispatcherServiceTest extends TestCase
{
    use MockeryHelper;

    private $dispatcher;

    private $queueDeployerDispatcherService;

    public function setUp()
    {
        parent::setUp();

        $this->dispatcher = $this->mock(Dispatcher::class);
        $this->queueDeployerDispatcherService = new QueueDeployerDispatcherService(
            $this->dispatcher
        );
    }

    public function test_Should_DispatchDeployer()
    {
        $deployment = $this->mock(Deployment::class);
        $projectId = 1;
        $deploymentId = 2;
        $runDeployer = new RunDeployer(
            $projectId,
            $deploymentId
        );

        $this->dispatcher
            ->shouldReceive('dispatch')
            ->with(\Hamcrest\Matchers::equalTo($runDeployer))
            ->once();
        $deployment
            ->shouldReceive('projectId->id')
            ->andReturn($projectId)
            ->once();
        $deployment
            ->shouldReceive('deploymentId->id')
            ->andReturn($deploymentId)
            ->once();

        $this->queueDeployerDispatcherService->dispatch($deployment);

        $this->assertTrue(true);
    }
}
