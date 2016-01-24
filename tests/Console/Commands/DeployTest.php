<?php

use App\Console\Commands\Deploy;

use Tests\Helpers\Factory;

class DeployTest extends TestCase
{
    use Tests\Helpers\ConsoleCommandTestHelper;

    use Tests\Helpers\MockeryHelper;

    protected $mockProjectRepository;

    protected $mockDeploymentRepository;

    protected $mockProcessBuilder;

    protected $mockProcess;

    public function setUp()
    {
        parent::setUp();

        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
        $this->mockDeploymentRepository = $this->mock('App\Repositories\Deployment\DeploymentInterface');
        $this->mockProcessBuilder = $this->mock('Symfony\Component\Process\ProcessBuilder');
        $this->mockProcess = $this->mockPartial('Symfony\Component\Process\Process');
    }

    public function test_Should_Work_When_StageArgumentIsNotSpecifiedAndDeployerIsNormalEnd()
    {
        $deployment = Factory::build('App\Models\Deployment', [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
            'user'       => new App\Models\User,
        ]);

        $project = Factory::build('App\Models\Project', [
            'id'          => 1,
            'name'        => 'Project 1',
            'recipe_path' => 'deploy.php',
            'created_at'  => new Carbon\Carbon,
            'updated_at'  => new Carbon\Carbon,
        ]);

        $this->mockDeploymentRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($deployment);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockDeploymentRepository
            ->shouldReceive('update')
            ->once();

        $this->mockProcess
            ->shouldReceive('run')
            ->once();

        $this->mockProcess
            ->shouldReceive('isSuccessful')
            ->once()
            ->andReturn(true);

        $this->mockProcess
            ->shouldReceive('getOutput')
            ->once();

        $this->mockProcess
            ->shouldReceive('getExitCode')
            ->once();

        $this->mockProcessBuilder
            ->shouldReceive('add')
            ->times(4)
            ->andReturn($this->mockProcessBuilder);

        $this->mockProcessBuilder
            ->shouldReceive('getProcess')
            ->once()
            ->andReturn($this->mockProcess);

        $command = new Deploy(
            $this->mockProjectRepository,
            $this->mockDeploymentRepository,
            $this->mockProcessBuilder
        );

        $this->runConsoleCommand($command, ['deployment-id' => 1]);

        $this->assertEquals('webloyer:deploy', $command->getName());
    }

    public function test_Should_Work_When_StageArgumentIsNotSpecifiedAndDeployerIsAbnormalEnd()
    {
        $deployment = Factory::build('App\Models\Deployment', [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
            'user'       => new App\Models\User,
        ]);

        $project = Factory::build('App\Models\Project', [
            'id'          => 1,
            'name'        => 'Project 1',
            'recipe_path' => 'deploy.php',
            'created_at'  => new Carbon\Carbon,
            'updated_at'  => new Carbon\Carbon,
        ]);

        $this->mockDeploymentRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($deployment);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockDeploymentRepository
            ->shouldReceive('update')
            ->once();

        $this->mockProcess
            ->shouldReceive('run')
            ->once();

        $this->mockProcess
            ->shouldReceive('isSuccessful')
            ->once()
            ->andReturn(false);

        $this->mockProcess
            ->shouldReceive('getErrorOutput')
            ->once();

        $this->mockProcess
            ->shouldReceive('getExitCode')
            ->once();

        $this->mockProcessBuilder
            ->shouldReceive('add')
            ->times(4)
            ->andReturn($this->mockProcessBuilder);

        $this->mockProcessBuilder
            ->shouldReceive('getProcess')
            ->once()
            ->andReturn($this->mockProcess);

        $command = new Deploy(
            $this->mockProjectRepository,
            $this->mockDeploymentRepository,
            $this->mockProcessBuilder
        );

        $this->runConsoleCommand($command, ['deployment-id' => 1]);

        $this->assertEquals('webloyer:deploy', $command->getName());
    }

    public function test_Should_Work_When_StageArgumentIsSpecifiedAndDeployerIsNormalEnd()
    {
        $deployment = Factory::build('App\Models\Deployment', [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
            'user'       => new App\Models\User,
        ]);

        $project = Factory::build('App\Models\Project', [
            'id'          => 1,
            'name'        => 'Project 1',
            'recipe_path' => 'deploy.php',
            'stage'       => 'staging',
            'created_at'  => new Carbon\Carbon,
            'updated_at'  => new Carbon\Carbon,
        ]);

        $this->mockDeploymentRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($deployment);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockDeploymentRepository
            ->shouldReceive('update')
            ->once();

        $this->mockProcess
            ->shouldReceive('run')
            ->once();

        $this->mockProcess
            ->shouldReceive('isSuccessful')
            ->once()
            ->andReturn(true);

        $this->mockProcess
            ->shouldReceive('getOutput')
            ->once();

        $this->mockProcess
            ->shouldReceive('getExitCode')
            ->once();

        $this->mockProcessBuilder
            ->shouldReceive('add')
            ->times(5)
            ->andReturn($this->mockProcessBuilder);

        $this->mockProcessBuilder
            ->shouldReceive('getProcess')
            ->once()
            ->andReturn($this->mockProcess);

        $command = new Deploy(
            $this->mockProjectRepository,
            $this->mockDeploymentRepository,
            $this->mockProcessBuilder
        );

        $this->runConsoleCommand($command, ['deployment-id' => 1]);

        $this->assertEquals('webloyer:deploy', $command->getName());
    }

    public function test_Should_Work_When_StageArgumentIsSpecifiedAndDeployerIsAbnormalEnd()
    {
        $deployment = Factory::build('App\Models\Deployment', [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
            'user'       => new App\Models\User,
        ]);

        $project = Factory::build('App\Models\Project', [
            'id'          => 1,
            'name'        => 'Project 1',
            'recipe_path' => 'deploy.php',
            'stage'       => 'staging',
            'created_at'  => new Carbon\Carbon,
            'updated_at'  => new Carbon\Carbon,
        ]);

        $this->mockDeploymentRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($deployment);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockDeploymentRepository
            ->shouldReceive('update')
            ->once();

        $this->mockProcess
            ->shouldReceive('run')
            ->once();

        $this->mockProcess
            ->shouldReceive('isSuccessful')
            ->once()
            ->andReturn(false);

        $this->mockProcess
            ->shouldReceive('getErrorOutput')
            ->once();

        $this->mockProcess
            ->shouldReceive('getExitCode')
            ->once();

        $this->mockProcessBuilder
            ->shouldReceive('add')
            ->times(5)
            ->andReturn($this->mockProcessBuilder);

        $this->mockProcessBuilder
            ->shouldReceive('getProcess')
            ->once()
            ->andReturn($this->mockProcess);

        $command = new Deploy(
            $this->mockProjectRepository,
            $this->mockDeploymentRepository,
            $this->mockProcessBuilder
        );

        $this->runConsoleCommand($command, ['deployment-id' => 1]);

        $this->assertEquals('webloyer:deploy', $command->getName());
    }
}
