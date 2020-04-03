<?php

use App\Specifications\DeploymentSpecification;

class DeploymentSpecificationTest extends TestCase
{
    use Tests\Helpers\MockeryHelper;

    protected $mockProjectModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
    }

    public function test_Should_GetSatisfyingElements()
    {
        $spec = new DeploymentSpecification;

        $deployment1 = $this->mockPartial('App\Models\Deployment');
        $deployment2 = $this->mockPartial('App\Models\Deployment');
        $deployment3 = $this->mockPartial('App\Models\Deployment');
        $deployment4 = $this->mockPartial('App\Models\Deployment');

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
