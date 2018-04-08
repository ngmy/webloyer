<?php

namespace Tests\Jobs;

use App\Jobs\RunDeployer;
use Ngmy\Webloyer\Webloyer\Application\Deployer\DeployerService;
use TestCase;
use Tests\Helpers\MockeryHelper;

class RunDeployerTest extends TestCase
{
    use MockeryHelper;

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_RunDeployer()
    {
        $projectId = 1;
        $deploymentId = 1;
        $runDeployer = $this->createRunDeployer([
            'projectId' => $projectId,
            'deploymentId' => $deploymentId,
        ]);

        $deployerServide = $this->mock(DeployerService::class);
        $deployerServide
            ->shouldReceive('runDeployer')
            ->with($projectId, $deploymentId)
            ->once();

        $runDeployer->handle($deployerServide);

        $this->assertTrue(true);
    }

    private function createRunDeployer(array $params = [])
    {
        $projectId = 1;
        $deploymentId = 1;

        extract($params);

        return new RunDeployer(
            $projectId,
            $deploymentId
        );
    }

}
