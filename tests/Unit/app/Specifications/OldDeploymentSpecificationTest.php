<?php

namespace Tests\Unit\app\Specifications;

use App\Specifications\OldDeploymentSpecification;

class OldDeploymentSpecificationTest extends TestCase
{
    use Tests\Helpers\MockeryHelper;

    protected $mockProjectModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
    }

    public function test_Should_GetSatisfyingElements_When_DaysToKeepDeploymentsIsSetAndKeepLastDeploymentIsFalseAndMaxNumberDeploymentsToKeepIsSet()
    {
        $date = new DateTime;
        $spec = new OldDeploymentSpecification($date);

        $deployment1 = $this->mockPartial('App\Models\Deployment');
        $deployment2 = $this->mockPartial('App\Models\Deployment');
        $deployment3 = $this->mockPartial('App\Models\Deployment');
        $deployment4 = $this->mockPartial('App\Models\Deployment');
        $deployment1->number = 1;
        $deployment2->number = 2;
        $deployment3->number = 3;
        $deployment4->number = 4;

        $deployments = collect([
            $deployment4,
            $deployment3,
            $deployment2,
            $deployment1,
        ]);

        $pastDaysToKeepDeployments = collect([
            $deployment2,
            $deployment1,
        ]);

        $pastNumToKeepDeployments = collect([
            $deployment3,
            $deployment2,
            $deployment1,
        ]);

        $this->mockProjectModel->days_to_keep_deployments = 1;
        $this->mockProjectModel->keep_last_deployment = 0;
        $this->mockProjectModel->max_number_of_deployments_to_keep = 1;

        $this->mockProjectModel
            ->shouldReceive('getDeployments')
            ->once()
            ->andReturn($deployments)
            ->shouldReceive('getDeploymentsWhereCreatedAtBefore')
            ->once()
            ->andReturn($pastDaysToKeepDeployments)
            ->shouldReceive('getDeploymentsWhereNumberBefore')
            ->once()
            ->andReturn($pastNumToKeepDeployments)
            ->shouldReceive('getLastDeployment')
            ->once()
            ->andReturn($deployment4);

        $oldDeployments = $spec->satisfyingElementsFrom($this->mockProjectModel);

        $this->assertEquals($deployment3, $oldDeployments[0]);
        $this->assertEquals($deployment2, $oldDeployments[1]);
        $this->assertEquals($deployment1, $oldDeployments[2]);
    }

    public function test_Should_GetSatisfyingElements_When_DaysToKeepDeploymentsIsNotSetAndKeepLastDeploymentIsFalseAndMaxNumberDeploymentsToKeepIsSet()
    {
        $date = new DateTime;
        $spec = new OldDeploymentSpecification($date);

        $deployment1 = $this->mockPartial('App\Models\Deployment');
        $deployment2 = $this->mockPartial('App\Models\Deployment');
        $deployment3 = $this->mockPartial('App\Models\Deployment');
        $deployment4 = $this->mockPartial('App\Models\Deployment');
        $deployment1->number = 1;
        $deployment2->number = 2;
        $deployment3->number = 3;
        $deployment4->number = 4;

        $deployments = collect([
            $deployment4,
            $deployment3,
            $deployment2,
            $deployment1,
        ]);

        $pastNumToKeepDeployments = collect([
            $deployment3,
            $deployment2,
            $deployment1,
        ]);

        $this->mockProjectModel->days_to_keep_deployments = null;
        $this->mockProjectModel->keep_last_deployment = 0;
        $this->mockProjectModel->max_number_of_deployments_to_keep = 1;

        $this->mockProjectModel
            ->shouldReceive('getDeployments')
            ->once()
            ->andReturn($deployments)
            ->shouldReceive('getDeploymentsWhereNumberBefore')
            ->once()
            ->andReturn($pastNumToKeepDeployments)
            ->shouldReceive('getLastDeployment')
            ->once()
            ->andReturn($deployment4);

        $oldDeployments = $spec->satisfyingElementsFrom($this->mockProjectModel);

        $this->assertEquals($deployment3, $oldDeployments[0]);
        $this->assertEquals($deployment2, $oldDeployments[1]);
        $this->assertEquals($deployment1, $oldDeployments[2]);
    }

    public function test_Should_GetSatisfyingElements_When_DaysToKeepDeploymentsIsSetAndKeepLastDeploymentIsFalseAndMaxNumberDeploymentsToKeepIsNotSet()
    {
        $date = new DateTime;
        $spec = new OldDeploymentSpecification($date);

        $deployment1 = $this->mockPartial('App\Models\Deployment');
        $deployment2 = $this->mockPartial('App\Models\Deployment');
        $deployment1->number = 1;
        $deployment2->number = 2;

        $deployments = collect([
            $deployment2,
            $deployment1,
        ]);

        $pastDaysToKeepDeployments = collect([
            $deployment2,
            $deployment1,
        ]);

        $this->mockProjectModel->days_to_keep_deployments = 1;
        $this->mockProjectModel->keep_last_deployment = 0;
        $this->mockProjectModel->max_number_of_deployments_to_keep = null;

        $this->mockProjectModel
            ->shouldReceive('getDeployments')
            ->once()
            ->andReturn($deployments)
            ->shouldReceive('getDeploymentsWhereCreatedAtBefore')
            ->once()
            ->andReturn($pastDaysToKeepDeployments);

        $oldDeployments = $spec->satisfyingElementsFrom($this->mockProjectModel);

        $this->assertEquals($deployment2, $oldDeployments[0]);
        $this->assertEquals($deployment1, $oldDeployments[1]);
    }

    public function test_Should_GetSatisfyingElements_When_DaysToKeepDeploymentsIsSetAndKeepLastDeploymentIsTrueMaxNumberDeploymentsToKeepIsNotSet()
    {
        $date = new DateTime;
        $spec = new OldDeploymentSpecification($date);

        $deployment1 = $this->mockPartial('App\Models\Deployment');
        $deployment2 = $this->mockPartial('App\Models\Deployment');
        $deployment1->number = 1;
        $deployment2->number = 2;

        $deployments = collect([
            $deployment2,
            $deployment1,
        ]);

        $pastDaysToKeepDeployments = collect([
            $deployment2,
            $deployment1,
        ]);

        $this->mockProjectModel->days_to_keep_deployments = 1;
        $this->mockProjectModel->keep_last_deployment = 1;
        $this->mockProjectModel->max_number_of_deployments_to_keep = null;

        $this->mockProjectModel
            ->shouldReceive('getDeployments')
            ->once()
            ->andReturn($deployments)
            ->shouldReceive('getDeploymentsWhereCreatedAtBefore')
            ->once()
            ->andReturn($pastDaysToKeepDeployments)
            ->shouldReceive('getLastDeployment')
            ->once()
            ->andReturn($deployment2);

        $oldDeployments = $spec->satisfyingElementsFrom($this->mockProjectModel);

        $this->assertEquals($deployment1, $oldDeployments[0]);
    }

    public function test_Should_GetSatisfyingElements_When_DaysToKeepDeploymentsIsNotSetAndKeepLastDeploymentIsFalseAndMaxNumberDeploymentsToKeepIsNotSet()
    {
        $date = new DateTime;
        $spec = new OldDeploymentSpecification($date);

        $deployment1 = $this->mockPartial('App\Models\Deployment');
        $deployment2 = $this->mockPartial('App\Models\Deployment');
        $deployment1->number = 1;
        $deployment2->number = 2;

        $deployments = collect([
            $deployment2,
            $deployment1,
        ]);

        $this->mockProjectModel->days_to_keep_deployments = null;
        $this->mockProjectModel->keep_last_deployment = 0;
        $this->mockProjectModel->max_number_of_deployments_to_keep = null;

        $this->mockProjectModel
            ->shouldReceive('getDeployments')
            ->once()
            ->andReturn($deployments);

        $oldDeployments = $spec->satisfyingElementsFrom($this->mockProjectModel);

        $this->assertEmpty($oldDeployments);
    }

    public function test_Should_GetSatisfyingElements_When_DeploymentsDoNotExists()
    {
        $date = new DateTime;
        $spec = new OldDeploymentSpecification($date);

        $deployments = collect([]);

        $this->mockProjectModel
            ->shouldReceive('getDeployments')
            ->once()
            ->andReturn($deployments);

        $oldDeployments = $spec->satisfyingElementsFrom($this->mockProjectModel);

        $this->assertEmpty($oldDeployments);
    }
}
