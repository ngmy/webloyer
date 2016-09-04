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

    protected $mockServerModel;

    protected $mockSettingRepositroy;

    protected $mockMailSettingEntity;

    protected $mockSettingModel;

    public function setUp()
    {
        parent::setUp();

        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
        $this->mockServerRepository = $this->mock('App\Repositories\Server\ServerInterface');
        $this->mockProcessBuilder = $this->mock('Symfony\Component\Process\ProcessBuilder');
        $this->mockProcess = $this->mockPartial('Symfony\Component\Process\Process');
        $this->mockDeployerFileDirector = $this->mock('App\Services\Deployment\DeployerFileDirector');
        $this->mockServerListFileBuilder = $this->mock('App\Services\Deployment\DeployerServerListFileBuilder');
        $this->mockRecipeFileBuilder = $this->mock('App\Services\Deployment\DeployerRecipeFileBuilder');
        $this->mockDeploymentFileBuilder = $this->mock('App\Services\Deployment\DeployerDeploymentFileBuilder');
        $this->mockNotifier = $this->mock('App\Services\Notification\NotifierInterface');
        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
        $this->mockServerModel = $this->mockPartial('App\Models\Server');
        $this->mockSettingRepositroy = $this->mock('App\Repositories\Setting\SettingInterface');
        $this->mockMailSettingEntity = $this->mock('App\Entities\Setting\MailSettingEntity');
        $this->mockSettingModel = $this->mockPartial('App\Models\Setting');
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
        $project = $this->mockProjectModel
            ->shouldReceive('getRecipes')
            ->once()
            ->andReturn([$recipe])
            ->mock();

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($this->mockServerModel);

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

        $this->mockServerListFileBuilder
            ->shouldReceive('setServer')
            ->once()
            ->andReturn($this->mockServerListFileBuilder);
        $this->mockServerListFileBuilder
            ->shouldReceive('setProject')
            ->once()
            ->andReturn($this->mockServerListFileBuilder);

        $this->mockRecipeFileBuilder
            ->shouldReceive('setRecipe')
            ->once();

        $this->mockDeploymentFileBuilder
            ->shouldReceive('setProject')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);
        $this->mockDeploymentFileBuilder
            ->shouldReceive('setServerListFile')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);
        $this->mockDeploymentFileBuilder
            ->shouldReceive('setRecipeFile')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);

        $job = new Deploy($deployment);

        $job->handle(
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier,
            $this->mockSettingRepositroy
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
        $project = $this->mockProjectModel
            ->shouldReceive('getRecipes')
            ->once()
            ->andReturn([$recipe])
            ->mock();

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($this->mockServerModel);

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

        $this->mockServerListFileBuilder
            ->shouldReceive('setServer')
            ->once()
            ->andReturn($this->mockServerListFileBuilder);
        $this->mockServerListFileBuilder
            ->shouldReceive('setProject')
            ->once()
            ->andReturn($this->mockServerListFileBuilder);

        $this->mockRecipeFileBuilder
            ->shouldReceive('setRecipe')
            ->once();

        $this->mockDeploymentFileBuilder
            ->shouldReceive('setProject')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);
        $this->mockDeploymentFileBuilder
            ->shouldReceive('setServerListFile')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);
        $this->mockDeploymentFileBuilder
            ->shouldReceive('setRecipeFile')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);

        $job = new Deploy($deployment);

        $job->handle(
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier,
            $this->mockSettingRepositroy
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
        $project = $this->mockProjectModel
            ->shouldReceive('getRecipes')
            ->once()
            ->andReturn([$recipe])
            ->mock();
        $project->email_notification_recipient = 'webloyer@example.com';

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($this->mockServerModel);

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

        $this->mockNotifier
            ->shouldReceive('to')
            ->once()
            ->andReturn($this->mockNotifier);

        $this->mockNotifier
            ->shouldReceive('notify')
            ->once();

        $this->mockMailSettingEntity
            ->shouldReceive('getDriver')
            ->once()
            ->shouldReceive('getFrom')
            ->twice()
            ->shouldReceive('getSmtpHost')
            ->once()
            ->shouldReceive('getSmtpPort')
            ->once()
            ->shouldReceive('getSmtpEncryption')
            ->once()
            ->shouldReceive('getSmtpUsername')
            ->once()
            ->shouldReceive('getSmtpPassword')
            ->once()
            ->shouldReceive('getSendmailPath')
            ->once();
        $this->mockSettingModel
            ->shouldReceive('getAttribute')
            ->with('attributes')
            ->andReturn($this->mockMailSettingEntity);
        $this->mockSettingRepositroy
            ->shouldReceive('byType')
            ->once()
            ->andReturn($this->mockSettingModel);

        $this->mockServerListFileBuilder
            ->shouldReceive('setServer')
            ->once()
            ->andReturn($this->mockServerListFileBuilder);
        $this->mockServerListFileBuilder
            ->shouldReceive('setProject')
            ->once()
            ->andReturn($this->mockServerListFileBuilder);

        $this->mockRecipeFileBuilder
            ->shouldReceive('setRecipe')
            ->once();

        $this->mockDeploymentFileBuilder
            ->shouldReceive('setProject')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);
        $this->mockDeploymentFileBuilder
            ->shouldReceive('setServerListFile')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);
        $this->mockDeploymentFileBuilder
            ->shouldReceive('setRecipeFile')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);

        $job = new Deploy($deployment);

        $job->handle(
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier,
            $this->mockSettingRepositroy
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
        $project = $this->mockProjectModel
            ->shouldReceive('getRecipes')
            ->once()
            ->andReturn([$recipe])
            ->mock();
        $project->email_notification_recipient = 'webloyer@example.com';

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($this->mockServerModel);

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

        $this->mockNotifier
            ->shouldReceive('to')
            ->once()
            ->andReturn($this->mockNotifier);

        $this->mockNotifier
            ->shouldReceive('notify')
            ->once();

        $this->mockMailSettingEntity
            ->shouldReceive('getDriver')
            ->once()
            ->shouldReceive('getFrom')
            ->twice()
            ->shouldReceive('getSmtpHost')
            ->once()
            ->shouldReceive('getSmtpPort')
            ->once()
            ->shouldReceive('getSmtpEncryption')
            ->once()
            ->shouldReceive('getSmtpUsername')
            ->once()
            ->shouldReceive('getSmtpPassword')
            ->once()
            ->shouldReceive('getSendmailPath')
            ->once();
        $this->mockSettingModel
            ->shouldReceive('getAttribute')
            ->with('attributes')
            ->andReturn($this->mockMailSettingEntity);
        $this->mockSettingRepositroy
            ->shouldReceive('byType')
            ->once()
            ->andReturn($this->mockSettingModel);

        $this->mockServerListFileBuilder
            ->shouldReceive('setServer')
            ->once()
            ->andReturn($this->mockServerListFileBuilder);
        $this->mockServerListFileBuilder
            ->shouldReceive('setProject')
            ->once()
            ->andReturn($this->mockServerListFileBuilder);

        $this->mockRecipeFileBuilder
            ->shouldReceive('setRecipe')
            ->once();

        $this->mockDeploymentFileBuilder
            ->shouldReceive('setProject')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);
        $this->mockDeploymentFileBuilder
            ->shouldReceive('setServerListFile')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);
        $this->mockDeploymentFileBuilder
            ->shouldReceive('setRecipeFile')
            ->once()
            ->andReturn($this->mockDeploymentFileBuilder);

        $job = new Deploy($deployment);

        $job->handle(
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier,
            $this->mockSettingRepositroy
        );
    }
}
