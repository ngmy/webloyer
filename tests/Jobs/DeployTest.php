<?php

namespace Tests\Jobs;

use App\Jobs\Deploy;

use Tests\Helpers\Factory;

class DeployTest extends \TestCase
{
    use \Tests\Helpers\MockeryHelper;

    protected $mockDeploymentRepository;

    protected $mockProjectRepository;

    protected $mockServerRepository;

    protected $mockProcessBuilder;

    protected $mockProcess;

    protected $mockDeployerFileDirector;

    protected $mockServerListFileBuilder;

    protected $mockRecipeFileBuilder;

    protected $mockDeploymentFileBuilder;

    protected $mockNotifier;

    public function setUp()
    {
        parent::setUp();

        $this->mockDeploymentRepository = $this->mock('App\Repositories\Deployment\DeploymentInterface');
        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
        $this->mockServerRepository = $this->mock('App\Repositories\Server\ServerInterface');
        $this->mockProcessBuilder = $this->mock('Symfony\Component\Process\ProcessBuilder');
        $this->mockProcess = $this->mockPartial('Symfony\Component\Process\Process');
        $this->mockDeployerFileDirector = $this->mock('App\Services\Deployment\DeployerFileDirector');
        $this->mockServerListFileBuilder = $this->mock('App\Services\Deployment\DeployerServerListFileBuilder');
        $this->mockRecipeFileBuilder = $this->mock('App\Services\DeploymentInterface\DeployerRecipeFileBuilder');
        $this->mockDeploymentFileBuilder = $this->mock('App\Services\Deployment\DeployerDeploymentFileBuilder');
        $this->mockNotifier = $this->mock('App\Services\Notification\NotifierInterface');
    }

    public function test_Should_Work_When_DeployerIsNormalEnd()
    {
        $deployment = Factory::build('App\Models\Deployment', [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new \Carbon\Carbon,
            'updated_at' => new \Carbon\Carbon,
            'user'       => new \App\Models\User,
        ]);

        $recipe = Factory::build('App\Models\Recipe', [
            'id'          => 1,
            'name'        => 'Recipe 1',
            'desctiption' => '',
            'body'        => '',
        ]);

        $project = Factory::build('App\Models\Project', [
            'id'         => 1,
            'name'       => 'Project 1',
            'recipe_id'  => 1,
            'stage'      => 'staging',
            'created_at' => new \Carbon\Carbon,
            'updated_at' => new \Carbon\Carbon,
            'recipes'    => [$recipe],
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once();

        $this->mockDeploymentRepository
            ->shouldReceive('update')
            ->once();

        $mockDeployerFile = $this->mock('App\Services\Deployment\DeployerFile')
            ->shouldReceive('getFullPath')
            ->once()
            ->mock();

        $this->mockDeployerFileDirector
            ->shouldReceive('construct')
            ->andReturn($mockDeployerFile)
            ->times(3);

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
            ->times(7)
            ->andReturn($this->mockProcessBuilder);

        $this->mockProcessBuilder
            ->shouldReceive('getProcess')
            ->once()
            ->andReturn($this->mockProcess);

        \Storage::shouldReceive('delete')
            ->times(1)
            ->andReturn(1);

        $job = new Deploy($deployment);

        $job->handle(
            $this->mockDeploymentRepository,
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier
        );
    }

    public function test_Should_Work_When_DeployerIsAbnormalEnd()
    {
        $deployment = Factory::build('App\Models\Deployment', [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new \Carbon\Carbon,
            'updated_at' => new \Carbon\Carbon,
            'user'       => new \App\Models\User,
        ]);

        $recipe = Factory::build('App\Models\Recipe', [
            'id'          => 1,
            'name'        => 'Recipe 1',
            'desctiption' => '',
            'body'        => '',
        ]);

        $project = Factory::build('App\Models\Project', [
            'id'         => 1,
            'name'       => 'Project 1',
            'recipe_id'  => 1,
            'stage'      => 'staging',
            'created_at' => new \Carbon\Carbon,
            'updated_at' => new \Carbon\Carbon,
            'recipes'    => [$recipe],
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once();

        $this->mockDeploymentRepository
            ->shouldReceive('update')
            ->once();

        $mockDeployerFile = $this->mock('App\Services\Deployment\DeployerFile')
            ->shouldReceive('getFullPath')
            ->once()
            ->mock();

        $this->mockDeployerFileDirector
            ->shouldReceive('construct')
            ->andReturn($mockDeployerFile)
            ->times(3);

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
            ->times(7)
            ->andReturn($this->mockProcessBuilder);

        $this->mockProcessBuilder
            ->shouldReceive('getProcess')
            ->once()
            ->andReturn($this->mockProcess);

        \Storage::shouldReceive('delete')
            ->times(1)
            ->andReturn(1);

        $job = new Deploy($deployment);

        $job->handle(
            $this->mockDeploymentRepository,
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier
        );
    }

    public function test_Should_WorkAndSendNotification_When_DeployerIsNormalEndAndEmailNotificationRecipientIsSet()
    {
        $deployment = Factory::build('App\Models\Deployment', [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new \Carbon\Carbon,
            'updated_at' => new \Carbon\Carbon,
            'user'       => new \App\Models\User,
        ]);

        $updatedDeployment = Factory::build('App\Models\Deployment', [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new \Carbon\Carbon,
            'updated_at' => new \Carbon\Carbon,
            'user'       => new \App\Models\User,
            'stauts'     => 0,
        ]);

        $recipe = Factory::build('App\Models\Recipe', [
            'id'          => 1,
            'name'        => 'Recipe 1',
            'desctiption' => '',
            'body'        => '',
        ]);

        $project = Factory::build('App\Models\Project', [
            'id'                           => 1,
            'name'                         => 'Project 1',
            'recipe_id'                    => 1,
            'stage'                        => 'staging',
            'email_notification_recipient' => 'webloyer@example.com',
            'created_at'                   => new \Carbon\Carbon,
            'updated_at'                   => new \Carbon\Carbon,
            'recipes'                      => [$recipe],
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once();

        $this->mockDeploymentRepository
            ->shouldReceive('update')
            ->once();

        $this->mockDeploymentRepository
            ->shouldReceive('byId')
            ->andReturn($updatedDeployment)
            ->once();

        $mockDeployerFile = $this->mock('App\Services\Deployment\DeployerFile')
            ->shouldReceive('getFullPath')
            ->once()
            ->mock();

        $this->mockDeployerFileDirector
            ->shouldReceive('construct')
            ->andReturn($mockDeployerFile)
            ->times(3);

        $this->mockProcess
            ->shouldReceive('run')
            ->once();

        $this->mockProcess
            ->shouldReceive('isSuccessful')
            ->twice()
            ->andReturn(true);

        $this->mockProcess
            ->shouldReceive('getOutput')
            ->once();

        $this->mockProcess
            ->shouldReceive('getExitCode')
            ->once();

        $this->mockProcessBuilder
            ->shouldReceive('add')
            ->times(7)
            ->andReturn($this->mockProcessBuilder);

        $this->mockProcessBuilder
            ->shouldReceive('getProcess')
            ->once()
            ->andReturn($this->mockProcess);

        \Storage::shouldReceive('delete')
            ->times(1)
            ->andReturn(1);

        $this->mockNotifier
            ->shouldReceive('to')
            ->once()
            ->andReturn($this->mockNotifier);

        $this->mockNotifier
            ->shouldReceive('notify')
            ->once();

        $job = new Deploy($deployment);

        $job->handle(
            $this->mockDeploymentRepository,
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier
        );
    }

    public function test_Should_WorkAndSendNotification_When_DeployerIsAbnormalEndAndEmailNotificationRecipientIsSet()
    {
        $deployment = Factory::build('App\Models\Deployment', [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new \Carbon\Carbon,
            'updated_at' => new \Carbon\Carbon,
            'user'       => new \App\Models\User,
        ]);

        $updatedDeployment = Factory::build('App\Models\Deployment', [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new \Carbon\Carbon,
            'updated_at' => new \Carbon\Carbon,
            'user'       => new \App\Models\User,
            'stauts'     => 1,
        ]);

        $recipe = Factory::build('App\Models\Recipe', [
            'id'          => 1,
            'name'        => 'Recipe 1',
            'desctiption' => '',
            'body'        => '',
        ]);

        $project = Factory::build('App\Models\Project', [
            'id'                           => 1,
            'name'                         => 'Project 1',
            'recipe_id'                    => 1,
            'stage'                        => 'staging',
            'email_notification_recipient' => 'webloyer@example.com',
            'created_at'                   => new \Carbon\Carbon,
            'updated_at'                   => new \Carbon\Carbon,
            'recipes'                      => [$recipe],
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once();

        $this->mockDeploymentRepository
            ->shouldReceive('update')
            ->once();

        $this->mockDeploymentRepository
            ->shouldReceive('byId')
            ->andReturn($updatedDeployment)
            ->once();

        $mockDeployerFile = $this->mock('App\Services\Deployment\DeployerFile')
            ->shouldReceive('getFullPath')
            ->once()
            ->mock();

        $this->mockDeployerFileDirector
            ->shouldReceive('construct')
            ->andReturn($mockDeployerFile)
            ->times(3);

        $this->mockProcess
            ->shouldReceive('run')
            ->once();

        $this->mockProcess
            ->shouldReceive('isSuccessful')
            ->twice()
            ->andReturn(false);

        $this->mockProcess
            ->shouldReceive('getErrorOutput')
            ->once();

        $this->mockProcess
            ->shouldReceive('getExitCode')
            ->once();

        $this->mockProcessBuilder
            ->shouldReceive('add')
            ->times(7)
            ->andReturn($this->mockProcessBuilder);

        $this->mockProcessBuilder
            ->shouldReceive('getProcess')
            ->once()
            ->andReturn($this->mockProcess);

        \Storage::shouldReceive('delete')
            ->times(1)
            ->andReturn(1);

        $this->mockNotifier
            ->shouldReceive('to')
            ->once()
            ->andReturn($this->mockNotifier);

        $this->mockNotifier
            ->shouldReceive('notify')
            ->once();

        $job = new Deploy($deployment);

        $job->handle(
            $this->mockDeploymentRepository,
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier
        );
    }
}
