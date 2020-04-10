<?php

namespace Tests\Feature\app\Jobs;

use App\Entities\Setting\MailSettingEntity;
use App\Jobs\Rollback;
use App\Models\Deployment;
use App\Models\Project;
use App\Models\Recipe;
use App\Models\Server;
use App\Models\Setting;
use App\Models\User;
use App\Repositories\Project\ProjectInterface;
use App\Repositories\Server\ServerInterface;
use App\Repositories\Setting\SettingInterface;
use App\Services\Deployment\DeployerDeploymentFileBuilder;
use App\Services\Deployment\DeployerFile;
use App\Services\Deployment\DeployerFileDirector;
use App\Services\Deployment\DeployerRecipeFileBuilder;
use App\Services\Deployment\DeployerServerListFileBuilder;
use App\Services\Notification\NotifierInterface;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Tests\TestCase;

class RollbackTest extends TestCase
{
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

    public function setUp(): void
    {
        parent::setUp();

        $this->mockProjectRepository = $this->mock(ProjectInterface::class);
        $this->mockServerRepository = $this->mock(ServerInterface::class);
        $this->mockProcessBuilder = $this->mock(ProcessBuilder::class);
        $this->mockProcess = $this->partialMock(Process::class);
        $this->mockDeployerFileDirector = $this->mock(DeployerFileDirector::class);
        $this->mockServerListFileBuilder = $this->mock(DeployerServerListFileBuilder::class);
        $this->mockRecipeFileBuilder = $this->mock(DeployerRecipeFileBuilder::class);
        $this->mockDeploymentFileBuilder = $this->mock(DeployerDeploymentFileBuilder::class);
        $this->mockNotifier = $this->mock(NotifierInterface::class);
        $this->mockProjectModel = $this->partialMock(Project::class);
        $this->mockServerModel = $this->partialMock(Server::class);
        $this->mockSettingRepositroy = $this->mock(SettingInterface::class);
        $this->mockMailSettingEntity = $this->mock(MailSettingEntity::class);
        $this->mockSettingModel = $this->partialMock(Setting::class);
    }

    public function test_Should_Work_When_DeployerIsNormalEnd()
    {
        $deployment = factory(Deployment::class)->make();

        $recipe = factory(Recipe::class)->make();

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

        $mockDeployerFile = $this->mock(DeployerFile::class)
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

        $job = new Rollback($deployment);

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
        $deployment = factory(Deployment::class)->make();

        $recipe = factory(Recipe::class)->make();

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

        $mockDeployerFile = $this->mock(DeployerFile::class)
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

        $job = new Rollback($deployment);

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
        $deployment = factory(Deployment::class)->make();

        $updatedDeployment = factory(Deployment::class)->make([
            'status' => 0,
        ]);

        $recipe = factory(Recipe::class)->make();

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
        $project->id = 1;
        $project->email_notification_recipient = 'webloyer@example.com';

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($this->mockServerModel);

        $mockDeployerFile = $this->mock(DeployerFile::class)
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

        $job = new Rollback($deployment);

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
        $deployment = factory(Deployment::class)->make();

        $updatedDeployment = factory(Deployment::class)->make([
            'stauts' => 1,
        ]);

        $recipe = factory(Recipe::class)->make();

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
        $project->id = 1;
        $project->email_notification_recipient = 'webloyer@example.com';

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockServerRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($this->mockServerModel);

        $mockDeployerFile = $this->mock(DeployerFile::class)
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

        $job = new Rollback($deployment);

        $job->handle(
            $this->mockProjectRepository,
            $this->mockServerRepository,
            $this->mockProcessBuilder,
            $this->mockNotifier,
            $this->mockSettingRepositroy
        );
    }
}
