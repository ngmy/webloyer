<?php

namespace Tests\Unit\app\Specifications;

use App\Models\Deployment;
use App\Models\Project;
use App\Specifications\DeploymentSpecification;
use Tests\TestCase;

class DeploymentSpecificationTest extends TestCase
{
    protected $mockProjectModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockProjectModel = $this->partialMock(Project::class);
    }

    public function testShouldGetSatisfyingElements()
    {
        $spec = new DeploymentSpecification();

        $deployment1 = $this->partialMock(Deployment::class);
        $deployment2 = $this->partialMock(Deployment::class);
        $deployment3 = $this->partialMock(Deployment::class);
        $deployment4 = $this->partialMock(Deployment::class);

        $deployments = collect([
            $deployment4,
            $deployment3,
            $deployment2,
            $deployment1,
        ]);

        $this->mockProjectModel
            ->shouldReceive('getDeployments')
            ->once()
            ->andReturn($deployments);

        $oldDeployments = $spec->satisfyingElementsFrom($this->mockProjectModel);

        $this->assertEquals($deployment4, $oldDeployments[0]);
        $this->assertEquals($deployment3, $oldDeployments[1]);
        $this->assertEquals($deployment2, $oldDeployments[2]);
        $this->assertEquals($deployment1, $oldDeployments[3]);
    }
}
