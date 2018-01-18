<?php

namespace Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm;

use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\Common\Validation\ValidableInterface;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Application\Deployer\DeployerService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm\DeploymentForm;
use Tests\Helpers\MockeryHelper;
use TestCase;

class DeploymentFormTest extends TestCase
{
    use MockeryHelper;

    private $validator;

    private $deploymentService;

    private $projectService;

    private $deployerService;

    private $deploymentForm;

    private $inputToSave = [
        'project_id' => null,
        'task'       => null,
        'user_id'    => null,
    ];

    public function setUp()
    {
        parent::setUp();

        $this->validator = $this->mock(ValidableInterface::class);
        $this->deploymentService = $this->mock(DeploymentService::class);
        $this->projectService = $this->mock(ProjectService::class);
        $this->deployerService = $this->mock(DeployerService::class);
        $this->deploymentForm = new DeploymentForm(
            $this->validator,
            $this->deploymentService,
            $this->projectService,
            $this->deployerService
        );
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_SucceedToSave_When_ValidationPasses()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(true);

        $this->deploymentService
            ->shouldReceive('getNextDeploymentIdOfProject->id');
        $mockDeployment = $this->mock(Deployment::class);
        $mockDeployment->shouldReceive('projectId->id');
        $mockDeployment->shouldReceive('deploymentId->id');
        $this->deploymentService
            ->shouldReceive('saveDeployment')
            ->andReturn($mockDeployment);

        $this->deployerService
            ->shouldReceive('dispatchDeployer');

        $actualResult = $this->deploymentForm->save($this->inputToSave);

        $this->assertTrue($actualResult, 'Expected save to succeed.');
    }

    public function test_Should_FailToSave_When_ValidationFails()
    {
        $this->validator
            ->shouldReceive('with->passes')
            ->andReturn(false);

        $actualResult = $this->deploymentForm->save($this->inputToSave);

        $this->assertFalse($actualResult, 'Expected save to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $expectedResult = new MessageBag();

        $this->validator
            ->shouldReceive('errors')
            ->andReturn($expectedResult);

        $actualResult = $this->deploymentForm->errors();

        $this->assertEquals($expectedResult, $actualResult);
    }
}
