<?php

use App\Services\Form\Deployment\DeploymentForm;
use Tests\Helpers\Factory;

class DeploymentFormTest extends TestCase
{
    use Tests\Helpers\MockeryHelper;

    protected $mockValidator;

    protected $mockProjectRepository;

    protected $mockDeployCommander;

    protected $mockProjectModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockValidator = $this->mock('App\Services\Validation\ValidableInterface');
        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
        $this->mockDeployCommander = $this->mock('App\Services\Deployment\DeployCommanderInterface');
        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
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

        $project = $this->mockProjectModel;
        $maxDeployment = Factory::build('App\Models\MaxDeployment', [
            'id'         => 1,
            'project_id' => $project->id,
            'number'     => 1,
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);
        $project->shouldReceive('getMaxDeployment')
            ->once()
            ->andReturn($maxDeployment)
            ->shouldReceive('addDeployment')
            ->once()
            ->shouldReceive('updateMaxDeployment')
            ->once()
            ->shouldReceive('getDeploymentByNumber')
            ->once()
            ->andReturn(true)
            ->mock();
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockDeployCommander
            ->shouldReceive('deploy')
            ->once()
            ->andReturn(true);

        $form = new DeploymentForm(
            $this->mockValidator,
            $this->mockProjectRepository,
            $this->mockDeployCommander
        );
        $result = $form->save([
            'project_id' => $project->id,
            'task'       => 'deploy'
        ]);

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

        $project = $this->mockProjectModel;
        $maxDeployment = Factory::build('App\Models\MaxDeployment', [
            'id'         => 1,
            'project_id' => $project->id,
            'number'     => 1,
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);
        $project->shouldReceive('getMaxDeployment')
            ->once()
            ->andReturn($maxDeployment)
            ->shouldReceive('addDeployment')
            ->once()
            ->shouldReceive('updateMaxDeployment')
            ->once()
            ->shouldReceive('getDeploymentByNumber')
            ->once()
            ->andReturn(true)
            ->mock();
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockDeployCommander
            ->shouldReceive('deploy')
            ->once()
            ->andReturn(true);

        $form = new DeploymentForm(
            $this->mockValidator,
            $this->mockProjectRepository,
            $this->mockDeployCommander
        );
        $result = $form->save([
            'project_id' => $project->id,
            'task'       => 'deploy'
        ]);

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

        $project = $this->mockProjectModel;
        $maxDeployment = Factory::build('App\Models\MaxDeployment', [
            'id'         => 1,
            'project_id' => $project->id,
            'number'     => 1,
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);
        $project->shouldReceive('getMaxDeployment')
            ->once()
            ->andReturn($maxDeployment)
            ->shouldReceive('addDeployment')
            ->once()
            ->shouldReceive('updateMaxDeployment')
            ->once()
            ->shouldReceive('getDeploymentByNumber')
            ->once()
            ->andReturn(false)
            ->mock();
        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $form = new DeploymentForm(
            $this->mockValidator,
            $this->mockProjectRepository,
            $this->mockDeployCommander
        );
        $result = $form->save([
            'project_id' => $project->id,
            'task'       => 'deploy'
        ]);

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
            $this->mockProjectRepository,
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
            $this->mockProjectRepository,
            $this->mockDeployCommander
        );
        $result = $form->errors();

        $this->assertEmpty($result);
    }
}
