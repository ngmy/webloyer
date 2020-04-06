<?php

namespace Tests\Unit\app\Services\Form\Deployment;

use App\Models\MaxDeployment;
use App\Models\Project;
use App\Repositories\Project\ProjectInterface;
use App\Services\Deployment\DeployCommanderInterface;
use App\Services\Form\Deployment\DeploymentForm;
use App\Services\Validation\ValidableInterface;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;
use Tests\TestCase;

class DeploymentFormTest extends TestCase
{
    protected $mockValidator;

    protected $mockProjectRepository;

    protected $mockDeployCommander;

    protected $mockProjectModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->mockValidator = $this->mock(ValidableInterface::class);
        $this->mockProjectRepository = $this->mock(ProjectInterface::class);
        $this->mockDeployCommander = $this->mock(DeployCommanderInterface::class);
        $this->mockProjectModel = $this->partialMock(Project::class);
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
        $maxDeployment = factory(MaxDeployment::class)->make([
            'project_id' => $project->id,
            'number'     => 1,
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
        $maxDeployment = factory(MaxDeployment::class)->make([
            'project_id' => $project->id,
            'number'     => 1,
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
        $maxDeployment = factory(MaxDeployment::class)->make([
            'project_id' => $project->id,
            'number'     => 1,
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
            ->andReturn(new MessageBag());

        $form = new DeploymentForm(
            $this->mockValidator,
            $this->mockProjectRepository,
            $this->mockDeployCommander
        );
        $result = $form->errors();

        $this->assertEmpty($result);
    }
}
