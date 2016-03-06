<?php

namespace Tests\Jobs;

use App\Jobs\Deploy;

use Tests\Helpers\Factory;

class DeployTest extends \TestCase
{
    use \Tests\Helpers\MockeryHelper;

    protected $mockProjectRepository;

    protected $mockServerRepository;

    protected $mockProcessBuilder;

    protected $mockProcess;

    protected $mockDeployerFileDirector;

    protected $mockServerListFileBuilder;

    protected $mockRecipeFileBuilder;

    protected $mockDeploymentFileBuilder;

    protected $mockNotifier;

    protected $mockProjectModel;

    protected $mockMailSettingRepositroy;

    protected $mockMailSettingEntity;

    public function setUp()
    {
        parent::setUp();

        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
        $this->mockServerRepository = $this->mock('App\Repositories\Server\ServerInterface');
        $this->mockProcessBuilder = $this->mock('Symfony\Component\Process\ProcessBuilder');
        $this->mockProcess = $this->mockPartial('Symfony\Component\Process\Process');
        $this->mockDeployerFileDirector = $this->mock('App\Services\Deployment\DeployerFileDirector');
        $this->mockServerListFileBuilder = $this->mock('App\Services\Deployment\DeployerServerListFileBuilder');
        $this->mockRecipeFileBuilder = $this->mock('App\Services\DeploymentInterface\DeployerRecipeFileBuilder');
        $this->mockDeploymentFileBuilder = $this->mock('App\Services\Deployment\DeployerDeploymentFileBuilder');
        $this->mockNotifier = $this->mock('App\Services\Notification\NotifierInterface');
        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
        $this->mockMailSettingRepositroy = $this->mock('App\Repositories\Setting\MailSettingInterface');
        $this->mockMailSettingEntity = $this->mockPartial('App\Entities\Setting\MailSettingEntity');
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

        $project = $this->mockProjectModel
            ->shouldReceive('updateDeployment')
            ->once()
            ->mock();
        $project->recipes = [$recipe];

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
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
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier,
            $this->mockMailSettingRepositroy
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

        $project = $this->mockProjectModel
            ->shouldReceive('updateDeployment')
            ->once()
            ->mock();
        $project->recipes = [$recipe];

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
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
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier,
            $this->mockMailSettingRepositroy
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

        $project = $this->mockProjectModel
            ->shouldReceive('updateDeployment')
            ->once()
            ->shouldReceive('getDeploymentByNumber')
            ->once()
            ->andReturn($updatedDeployment)
            ->mock();
        $project->recipes = [$recipe];
        $project->email_notification_recipient = 'webloyer@example.com';

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
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

        $this->mockMailSettingRepositroy
            ->shouldReceive('all')
            ->once()
            ->andReturn($this->mockMailSettingEntity);

        $job = new Deploy($deployment);

        $job->handle(
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier,
            $this->mockMailSettingRepositroy
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

        $project = $this->mockProjectModel
            ->shouldReceive('updateDeployment')
            ->once()
            ->shouldReceive('getDeploymentByNumber')
            ->once()
            ->andReturn($updatedDeployment)
            ->mock();
        $project->recipes = [$recipe];
        $project->email_notification_recipient = 'webloyer@example.com';

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
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

        $this->mockMailSettingRepositroy
            ->shouldReceive('all')
            ->once()
            ->andReturn($this->mockMailSettingEntity);

        $job = new Deploy($deployment);

        $job->handle(
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier,
            $this->mockMailSettingRepositroy
        );
    }
}
