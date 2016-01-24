<?php

use App\Services\Form\Deployment\DeploymentForm;

class DeploymentFormTest extends TestCase
{
    use Tests\Helpers\MockeryHelper;

    protected $mockValidator;

    protected $mockDeploymentRepository;

    protected $mockDeployCommander;

    public function setUp()
    {
        parent::setUp();

        $this->mockValidator = $this->mock('App\Services\Validation\ValidableInterface');
        $this->mockDeploymentRepository = $this->mock('App\Repositories\Deployment\DeploymentInterface');
        $this->mockDeployCommander = $this->mock('App\Services\Deployment\DeployCommanderInterface');
    }

    public function test_Should_SucceedToSave_When_ValidationPasses()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $this->mockDeploymentRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn(true);

        $this->mockDeployCommander
            ->shouldReceive('deploy')
            ->once()
            ->andReturn(true);

        $form = new DeploymentForm(
            $this->mockValidator,
            $this->mockDeploymentRepository,
            $this->mockDeployCommander
        );
        $result = $form->save(['task' => 'deploy']);

        $this->assertTrue($result, 'Expected save to succeed.');
    }

    public function test_Should_SucceedToSave_When_ValidationPassesAndSaveToDatabaseSucceeds()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $this->mockDeploymentRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn(true);

        $this->mockDeployCommander
            ->shouldReceive('deploy')
            ->once()
            ->andReturn(true);

        $form = new DeploymentForm(
            $this->mockValidator,
            $this->mockDeploymentRepository,
            $this->mockDeployCommander
        );
        $result = $form->save(['task' => 'deploy']);

        $this->assertTrue($result, 'Expected save to succeed.');
    }

    public function test_Should_FailToSave_When_ValidationPassesAndSaveToDatabaseFails()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(true);

        $this->mockDeploymentRepository
            ->shouldReceive('create')
            ->once()
            ->andReturn(false);

        $form = new DeploymentForm(
            $this->mockValidator,
            $this->mockDeploymentRepository,
            $this->mockDeployCommander
        );
        $result = $form->save(['task' => 'deploy']);

        $this->assertFalse($result, 'Expected save to fail.');
    }

    public function test_Should_FailToSave_When_ValidationFails()
    {
        $this->mockValidator
            ->shouldReceive('with')
            ->once()
            ->andReturn($this->mockValidator);
        $this->mockValidator
            ->shouldReceive('passes')
            ->once()
            ->andReturn(false);

        $form = new DeploymentForm(
            $this->mockValidator,
            $this->mockDeploymentRepository,
            $this->mockDeployCommander
        );
        $result = $form->save([]);

        $this->assertFalse($result, 'Expected save to fail.');
    }

    public function test_Should_GetValidationErrors()
    {
        $this->mockValidator
            ->shouldReceive('errors')
            ->once()
            ->andReturn(new Illuminate\Support\MessageBag);

        $form = new DeploymentForm(
            $this->mockValidator,
            $this->mockDeploymentRepository,
            $this->mockDeployCommander
        );
        $result = $form->errors();

        $this->assertEmpty($result);
    }
}
