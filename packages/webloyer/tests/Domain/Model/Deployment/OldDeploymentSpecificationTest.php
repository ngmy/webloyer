<?php

namespace Ngmy\Webloyer\Webloyer\Domain\Model\Deployment;

use Carbon\Carbon;
use DateTimeImmutable;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentRepositoryInterface;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\OldDeploymentSpecification;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\KeepLastDeployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Tests\Helpers\MockeryHelper;
use TestCase;

class OldDeploymentSpecificationTest extends TestCase
{
    use MockeryHelper;

    private $oldDeploymentSpecification;

    private $project;

    private $currentDate;

    public function setUp()
    {
        parent::setUp();

        $this->project = $this->mock(Project::class);
        $this->currentDate = $this->mock(DateTimeImmutable::class);
        $this->oldDeploymentSpecification = new OldDeploymentSpecification(
            $this->project,
            $this->currentDate
        );
    }

    public function test_Should_GetSatisfyingElements_When_DaysToKeepDeploymentsIsSetAndKeepLastDeploymentIsOffAndMaxNumberDeploymentsToKeepIsSet()
    {
        $deployment1 = $this->mock(Deployment::class);
        $deployment2 = $this->mock(Deployment::class);
        $deployment3 = $this->mock(Deployment::class);
        $deployment4 = $this->mock(Deployment::class);

        $deployments = [
            $deployment4,
            $deployment3,
            $deployment2,
            $deployment1,
        ];
        $daysToKeepDeployments = 1;
        $keepLastDeployment = KeepLastDeployment::off();
        $baseDate = '2018-03-27 00:00:00';
        $maxNumberOfDeploymentsToKeep = 1;
        $expectedResult = [
            $deployment3,
            $deployment2,
            $deployment1,
        ];

        $deployment1
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(1);
        $deployment1
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-24 00:00:00'));
        $deployment2
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(2);
        $deployment2
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-25 00:00:00'));
        $deployment3
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(3);
        $deployment3
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-26 00:00:00'));
        $deployment4
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(4);
        $deployment4
            ->shouldReceive('createdAt')
            ->andReturn(new Carbon('2018-03-27 00:00:00'));

        $this->checkSatisfyingElementsFrom(
            $deployments,
            $daysToKeepDeployments,
            $keepLastDeployment,
            $baseDate,
            $maxNumberOfDeploymentsToKeep,
            $expectedResult
        );
    }

    public function test_Should_GetSatisfyingElements_When_DaysToKeepDeploymentsIsNotSetAndKeepLastDeploymentIsOffAndMaxNumberDeploymentsToKeepIsSet()
    {
        $deployment1 = $this->mock(Deployment::class);
        $deployment2 = $this->mock(Deployment::class);
        $deployment3 = $this->mock(Deployment::class);
        $deployment4 = $this->mock(Deployment::class);

        $deployments = [
            $deployment4,
            $deployment3,
            $deployment2,
            $deployment1,
        ];
        $daysToKeepDeployments = null;
        $keepLastDeployment = null;
        $baseDate = null;
        $maxNumberOfDeploymentsToKeep = 1;
        $expectedResult = [
            $deployment3,
            $deployment2,
            $deployment1,
        ];

        $deployment1
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(1);
        $deployment1
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-24 00:00:00'));
        $deployment2
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(2);
        $deployment2
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-25 00:00:00'));
        $deployment3
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(3);
        $deployment3
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-26 00:00:00'));
        $deployment4
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(4);
        $deployment4
            ->shouldReceive('createdAt')
            ->andReturn(new Carbon('2018-03-27 00:00:00'));

        $this->checkSatisfyingElementsFrom(
            $deployments,
            $daysToKeepDeployments,
            $keepLastDeployment,
            $baseDate,
            $maxNumberOfDeploymentsToKeep,
            $expectedResult
        );
    }

    public function test_Should_GetSatisfyingElements_When_DaysToKeepDeploymentsIsSetAndKeepLastDeploymentIsOffAndMaxNumberDeploymentsToKeepIsNotSet()
    {
        $deployment1 = $this->mock(Deployment::class);
        $deployment2 = $this->mock(Deployment::class);
        $deployment3 = $this->mock(Deployment::class);
        $deployment4 = $this->mock(Deployment::class);

        $deployments = [
            $deployment4,
            $deployment3,
            $deployment2,
            $deployment1,
        ];
        $daysToKeepDeployments = 1;
        $keepLastDeployment = KeepLastDeployment::off();
        $baseDate = '2018-03-27 00:00:00';
        $maxNumberOfDeploymentsToKeep = null;
        $expectedResult = [
            $deployment3,
            $deployment2,
            $deployment1,
        ];

        $deployment1
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(1);
        $deployment1
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-24 00:00:00'));
        $deployment2
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(2);
        $deployment2
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-25 00:00:00'));
        $deployment3
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(3);
        $deployment3
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-26 00:00:00'));
        $deployment4
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(4);
        $deployment4
            ->shouldReceive('createdAt')
            ->andReturn(new Carbon('2018-03-27 00:00:00'));

        $this->checkSatisfyingElementsFrom(
            $deployments,
            $daysToKeepDeployments,
            $keepLastDeployment,
            $baseDate,
            $maxNumberOfDeploymentsToKeep,
            $expectedResult
        );
    }

    public function test_Should_GetSatisfyingElements_When_DaysToKeepDeploymentsIsNotSetAndKeepLastDeploymentIsOffAndMaxNumberDeploymentsToKeepIsNotSet()
    {
        $deployment1 = $this->mock(Deployment::class);
        $deployment2 = $this->mock(Deployment::class);
        $deployment3 = $this->mock(Deployment::class);
        $deployment4 = $this->mock(Deployment::class);

        $deployments = [
            $deployment4,
            $deployment3,
            $deployment2,
            $deployment1,
        ];
        $daysToKeepDeployments = null;
        $keepLastDeployment = null;
        $baseDate = null;
        $maxNumberOfDeploymentsToKeep = null;
        $expectedResult = [];

        $deployment1
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(1);
        $deployment1
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-24 00:00:00'));
        $deployment2
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(2);
        $deployment2
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-25 00:00:00'));
        $deployment3
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(3);
        $deployment3
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-26 00:00:00'));
        $deployment4
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(4);
        $deployment4
            ->shouldReceive('createdAt')
            ->andReturn(new Carbon('2018-03-27 00:00:00'));

        $this->checkSatisfyingElementsFrom(
            $deployments,
            $daysToKeepDeployments,
            $keepLastDeployment,
            $baseDate,
            $maxNumberOfDeploymentsToKeep,
            $expectedResult
        );
    }

    public function test_Should_GetSatisfyingElements_When_DeploymentsDoNotExists()
    {
        $daysToKeepDeployments = null;
        $keepLastDeployment = null;
        $baseDate = null;
        $maxNumberOfDeploymentsToKeep = null;
        $deployments = [];
        $expectedResult = [];

        $this->checkSatisfyingElementsFrom(
            $deployments,
            $daysToKeepDeployments,
            $keepLastDeployment,
            $baseDate,
            $maxNumberOfDeploymentsToKeep,
            $expectedResult
        );
    }

    public function test_Should_GetSatisfyingElementsAndKeepLastDeployment_When_AllDeploymentsArePastDaysToKeepAndKeepLastDeploymentIsOn()
    {
        $deployment1 = $this->mock(Deployment::class);
        $deployment2 = $this->mock(Deployment::class);
        $deployment3 = $this->mock(Deployment::class);
        $deployment4 = $this->mock(Deployment::class);

        $deployments = [
            $deployment4,
            $deployment3,
            $deployment2,
            $deployment1,
        ];
        $daysToKeepDeployments = 1;
        $keepLastDeployment = KeepLastDeployment::on();
        $baseDate = '2018-03-28 00:00:00';
        $maxNumberOfDeploymentsToKeep = null;
        $expectedResult = [
            $deployment3,
            $deployment2,
            $deployment1,
        ];

        $deployment1
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(1);
        $deployment1
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-24 00:00:00'));
        $deployment2
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(2);
        $deployment2
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-25 00:00:00'));
        $deployment3
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(3);
        $deployment3
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-26 00:00:00'));
        $deployment4
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(4);
        $deployment4
            ->shouldReceive('createdAt')
            ->andReturn(new Carbon('2018-03-27 00:00:00'));

        $this->checkSatisfyingElementsFrom(
            $deployments,
            $daysToKeepDeployments,
            $keepLastDeployment,
            $baseDate,
            $maxNumberOfDeploymentsToKeep,
            $expectedResult
        );
    }

    public function test_Should_GetSatisfyingElementsAndNotKeepLastDeployment_When_AllDeploymentsArePastDaysToKeepAndKeepLastDeploymentIsOff()
    {
        $deployment1 = $this->mock(Deployment::class);
        $deployment2 = $this->mock(Deployment::class);
        $deployment3 = $this->mock(Deployment::class);
        $deployment4 = $this->mock(Deployment::class);

        $deployments = [
            $deployment4,
            $deployment3,
            $deployment2,
            $deployment1,
        ];
        $daysToKeepDeployments = 1;
        $keepLastDeployment = KeepLastDeployment::off();
        $baseDate = '2018-03-28 00:00:00';
        $maxNumberOfDeploymentsToKeep = null;
        $expectedResult = [
            $deployment4,
            $deployment3,
            $deployment2,
            $deployment1,
        ];

        $deployment1
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(1);
        $deployment1
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-24 00:00:00'));
        $deployment2
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(2);
        $deployment2
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-25 00:00:00'));
        $deployment3
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(3);
        $deployment3
            ->shouldReceive('createdAt')
            ->withNoArgs()
            ->andReturn(new Carbon('2018-03-26 00:00:00'));
        $deployment4
            ->shouldReceive('deploymentId->id')
            ->withNoArgs()
            ->andReturn(4);
        $deployment4
            ->shouldReceive('createdAt')
            ->andReturn(new Carbon('2018-03-27 00:00:00'));

        $this->checkSatisfyingElementsFrom(
            $deployments,
            $daysToKeepDeployments,
            $keepLastDeployment,
            $baseDate,
            $maxNumberOfDeploymentsToKeep,
            $expectedResult
        );
    }


    private function checkSatisfyingElementsFrom($deployments, $daysToKeepDeployments, $keepLastDeployment, $baseDate, $maxNumberOfDeploymentsToKeep, $expectedResult)
    {
        $projectId = 1;
        $deploymentRepository = $this->mock(DeploymentRepositoryInterface::class);

        $this->project
            ->shouldReceive('projectId->id')
            ->andReturn($projectId)
            ->once();

        if (empty($deployments)) {
            $deploymentRepository
                ->shouldReceive('deployments->all')
                ->andReturn($deployments)
                ->once();
        } else {
            $deploymentRepository
                ->shouldReceive('deployments->all')
                ->andReturn($deployments)
                ->once();
            $this->project
                ->shouldReceive('daysToKeepDeployments')
                ->withNoArgs()
                ->andReturn($daysToKeepDeployments)
                ->once();
            $this->project
                ->shouldReceive('maxNumberOfDeploymentsToKeep')
                ->withNoArgs()
                ->andReturn($maxNumberOfDeploymentsToKeep)
                ->once();

            if (!is_null($daysToKeepDeployments)) {
                $this->project
                    ->shouldReceive('keepLastDeployment')
                    ->withNoArgs()
                    ->andReturn($keepLastDeployment)
                    ->once();
                $this->currentDate
                    ->shouldReceive('modify')
                    ->with('-' . $daysToKeepDeployments . ' days')
                    ->once()
                    ->andReturn(new Carbon($baseDate));
            }
        }

        $actualResult = $this->oldDeploymentSpecification->satisfyingElementsFrom($deploymentRepository);

        $this->assertEquals($expectedResult, $actualResult);
    }
}
