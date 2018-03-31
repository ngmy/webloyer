<?php

namespace Ngmy\Webloyer\Webloyer\Application\Deployer;

use App;
use Illuminate\Contracts\View\View;
use Ngmy\Webloyer\Common\Notification\NotifierInterface;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentPresenter;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Application\Deployer\DeployerService;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;
use Ngmy\Webloyer\Webloyer\Application\Server\ServerService;
use Ngmy\Webloyer\Webloyer\Application\Setting\SettingService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileDirector;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerDeploymentFileBuilder;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerRecipeFileBuilder;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerServerListFileBuilder;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Setting\MailSetting;
use Ngmy\Webloyer\Webloyer\Domain\Service\Deployer\DeployerDispatcherServiceInterface;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;
use Tests\Helpers\MockeryHelper;
use TestCase;
use View as ViewFacade;

class DeployerServiceTest extends TestCase
{
    use MockeryHelper;

    private $deployerService;

    private $deployerDispatcherService;

    private $deploymentService;

    private $projectService;

    private $serverService;

    private $recipeService;

    private $settingService;

    private $processBuilder;

    private $notifier;

    private $userService;

    public function setUp()
    {
        parent::setUp();

        $this->deployerDispatcherService = $this->mock(DeployerDispatcherServiceInterface::class);
        $this->deploymentService = $this->mock(DeploymentService::class);
        $this->projectService = $this->mock(ProjectService::class);
        $this->serverService = $this->mock(ServerService::class);
        $this->recipeService = $this->mock(RecipeService::class);
        $this->settingService = $this->mock(SettingService::class);
        $this->processBuilder = $this->mock(ProcessBuilder::class);
        $this->notifier = $this->mock(NotifierInterface::class);
        $this->userService = $this->mock(UserService::class);
        $this->deployerService = new DeployerService(
            $this->deployerDispatcherService,
            $this->deploymentService,
            $this->projectService,
            $this->serverService,
            $this->recipeService,
            $this->settingService,
            $this->processBuilder,
            $this->notifier,
            $this->userService
        );
        $this->deployerFileDirector = $this->mock(DeployerFileDirector::class);
        $this->deployerRecipeFileBuilder = $this->mock(DeployerRecipeFileBuilder::class);
        $this->deployerServerListFileBuilder = $this->mock(DeployerServerListFileBuilder::class);
        $this->deployerDeploymentFileBuilder = $this->mock(DeployerDeploymentFileBuilder::class);

        App::instance(DeployerFileDirector::class, $this->deployerFileDirector);
        App::instance(DeployerRecipeFileBuilder::class, $this->deployerRecipeFileBuilder);
        App::instance(DeployerServerListFileBuilder::class, $this->deployerServerListFileBuilder);
        App::instance(DeployerDeploymentFileBuilder::class, $this->deployerDeploymentFileBuilder);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_DispatchDeployer()
    {
        $projectId = 1;
        $deploymentId = 1;
        $deployment = $this->mock(Deployment::class);

        $this->deploymentService
            ->shouldReceive('getDeploymentById')
            ->with($projectId, $deploymentId)
            ->andReturn($deployment)
            ->once();
        $this->deployerDispatcherService
            ->shouldReceive('dispatch')
            ->with($deployment)
            ->once();

        $this->deployerService->dispatchDeployer($projectId, $deploymentId);

        $this->assertTrue(true);
    }

    public function test_Should_RunDeployer_When_DeployerProcessSucceedsAndEmailNotificationRecipientIsNotSet()
    {
        $this->checkRunDeployer([
            'processIsSuccessful' => true,
            'emailNotificationRecipient' => '',
        ]);
    }

    public function test_Should_RunDeployer_When_DeployerProcessFailsAndEmailNotificationRecipientIsNotSet()
    {
        $this->checkRunDeployer([
            'processIsSuccessful' => false,
            'emailNotificationRecipient' => '',
        ]);
    }

    public function test_Should_RunDeployer_When_DeployerProcessSucceedsAndEmailNotificationRecipientIsSetAndMailSettingFromAddressIsNotSetAndMailSettingFromNameIsNotSet()
    {
        $this->checkRunDeployer([
            'processIsSuccessful' => true,
            'emailNotificationRecipient' => 'test@example.com',
        ]);
    }

    public function test_Should_RunDeployer_When_DeployerProcessFailsAndEmailNotificationRecipientIsSetAndMailSettingFromAddressIsNotSetAndMailSettingFromNameIsNotSet()
    {
        $this->checkRunDeployer([
            'processIsSuccessful' => false,
            'emailNotificationRecipient' => 'test@example.com',
        ]);
    }

    public function test_Should_RunDeployer_When_EmailNotificationRecipienIsSetAndMailSettingFromAddressIsSetAndMailSettingFromNameIsSet()
    {
        $this->checkRunDeployer([
            'emailNotificationRecipient' => 'test@example.com',
            'mailSettingFrom' => [
                'address' => 'webloyer@example.com',
                'name' => 'webloyer',
            ],
        ]);
    }

    private function checkRunDeployer(array $params)
    {
        $iProjectId = 1;
        $project = $this->mock(Project::class);
        $projectStage = '';
        $projectName = '';

        $iDeploymentId = 1;
        $deployment = $this->mock(Deployment::class);
        $deploymentPresenter = new DeploymentPresenter($deployment, new AnsiToHtmlConverter());
        $deploymentTask = '';
        $iDeployedUserId = 1;
        $deployedUser = $this->mock(User::class);

        $iServerId = 1;
        $serverId = new ServerId($iServerId);
        $server = $this->mock(Server::class);

        $iRecipeId = 1;
        $recipeId = new RecipeId($iRecipeId);
        $recipe = $this->mock(Recipe::class);
        $recipeIds = [$recipeId];

        $serverListFile = $this->mock(DeployerFile::class);

        $recipeFile = $this->mock(DeployerFile::class);
        $recipeFiles = [$recipeFile];

        $deploymentFile = $this->mock(DeployerFile::class);
        $deploymentFileFullPath = '';

        $process = $this->mock(Process::class);
        $processIsSuccessful = true;
        $processExitCode = 0;
        $processOutput = '';
        $processTimeout = 600;

        $deployerExecutable = base_path('vendor/bin/dep');

        $mailSetting = $this->mock(MailSetting::class);
        $mailSettingFrom = [
            'address' => null,
            'name' => null,
        ];
        $mailSettingDriver = '';
        $mailSettingSmtpHost = '';
        $mailSettingSmtpPort = '';
        $mailSettingSmtpEncryption = '';
        $mailSettingSmtpUserName = '';
        $mailSettingSmtpPassword = '';
        $mailSettingSendmailPath = '';

        $emailNotificationView = $this->mock(View::class);
        $emailNotidicationMessage = '';
        $emailNotificationRecipient = '';

        extract($params);

        $deploymentStatus = $processIsSuccessful ? 'success' : 'failure';
        $emailNotificationSubject = "Deployment of $projectName #$iDeploymentId finished: $deploymentStatus";

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($iProjectId)
            ->andReturn($project)
            ->once();

        $this->deploymentService
            ->shouldReceive('saveDeployment')
            ->with(
                $iProjectId,
                $iDeploymentId,
                $deploymentTask,
                $processExitCode,
                $processOutput,
                $iDeployedUserId
            )
            ->once();

        $this->serverService
            ->shouldReceive('getServerById')
            ->with($iServerId)
            ->andReturn($server)
            ->once();

        $this->recipeService
            ->shouldReceive('getRecipeById')
            ->with($iRecipeId)
            ->andReturn($recipe)
            ->times(count($recipeIds));

        $this->deployerServerListFileBuilder
            ->shouldReceive('setServer')
            ->with($server)
            ->andReturn($this->deployerServerListFileBuilder)
            ->once();
        $this->deployerServerListFileBuilder
            ->shouldReceive('setProject')
            ->with($project)
            ->andReturn($this->deployerServerListFileBuilder)
            ->once();

        $this->deployerFileDirector
            ->shouldReceive('construct')
            ->withNoArgs()
            ->andReturn($serverListFile, $recipeFile, $deploymentFile)
            ->times(1 + count($recipeIds) + 1);

        $this->deployerRecipeFileBuilder
            ->shouldReceive('setRecipe')
            ->with($recipe)
            ->andReturn($this->deployerRecipeFileBuilder)
            ->times(count($recipeIds));

        $this->deployerDeploymentFileBuilder
            ->shouldReceive('setProject')
            ->with($project)
            ->andReturn($this->deployerDeploymentFileBuilder)
            ->once();
        $this->deployerDeploymentFileBuilder
            ->shouldReceive('setServerListFile')
            ->with($serverListFile)
            ->andReturn($this->deployerDeploymentFileBuilder)
            ->once();
        $this->deployerDeploymentFileBuilder
            ->shouldReceive('setRecipeFile')
            ->with($recipeFiles)
            ->andReturn($this->deployerDeploymentFileBuilder)
            ->once();

        $this->processBuilder
            ->shouldReceive('add')
            ->with($deployerExecutable)
            ->andReturn($this->processBuilder)
            ->once();
        $this->processBuilder
            ->shouldReceive('add')
            ->with("-f=$deploymentFileFullPath")
            ->andReturn($this->processBuilder)
            ->once();
        $this->processBuilder
            ->shouldReceive('add')
            ->with('--ansi')
            ->andReturn($this->processBuilder)
            ->once();
        $this->processBuilder
            ->shouldReceive('add')
            ->with('-n')
            ->andReturn($this->processBuilder)
            ->once();
        $this->processBuilder
            ->shouldReceive('add')
            ->with('-vv')
            ->andReturn($this->processBuilder)
            ->once();
        $this->processBuilder
            ->shouldReceive('add')
            ->with($deploymentTask)
            ->andReturn($this->processBuilder)
            ->once();
        $this->processBuilder
            ->shouldReceive('add')
            ->with($projectStage)
            ->andReturn($this->processBuilder)
            ->once();
        $this->processBuilder
            ->shouldReceive('getProcess')
            ->withNoArgs()
            ->andReturn($process)
            ->once();

        $project
            ->shouldReceive('serverId->id')
            ->withNoArgs()
            ->andReturn($iServerId)
            ->once();
        $project
            ->shouldReceive('recipeIds')
            ->withNoArgs()
            ->andReturn($recipeIds)
            ->once();
        $project
            ->shouldReceive('stage')
            ->withNoArgs()
            ->andReturn($projectStage)
            ->once();

        $deployment
            ->shouldReceive('task->value')
            ->withNoArgs()
            ->andReturn($deploymentTask)
            ->times(2);

        $deploymentFile
            ->shouldReceive('getFullPath')
            ->withNoArgs()
            ->andReturn($deploymentFileFullPath)
            ->once();

        $process
            ->shouldReceive('setTimeout')
            ->with($processTimeout)
            ->once();
        $process
            ->shouldReceive('run')
            ->with(\Mockery::type('callable'))
            ->once();
        $process
            ->shouldReceive('getExitCode')
            ->withNoArgs()
            ->andReturn($processExitCode)
            ->once();

        if ($processIsSuccessful) {
            $process
                ->shouldReceive('getOutput')
                ->withNoArgs()
                ->andReturn($processOutput)
                ->once();
        } else {
            $process
                ->shouldReceive('getErrorOutput')
                ->withNoArgs()
                ->andReturn($processOutput)
                ->once();
        }

        if (empty($emailNotificationRecipient)) {
            $this->deploymentService
                ->shouldReceive('getDeploymentById')
                ->with($iProjectId, $iDeploymentId)
                ->andReturn($deployment)
                ->once();

            $project
                ->shouldReceive('emailNotificationRecipient')
                ->withNoArgs()
                ->andReturn($emailNotificationRecipient)
                ->once();

            $deployment
                ->shouldReceive('projectId->id')
                ->withNoArgs()
                ->andReturn($iProjectId)
                ->once();
            $deployment
                ->shouldReceive('deploymentId->id')
                ->withNoArgs()
                ->andReturn($iDeploymentId)
                ->once();
            $deployment
                ->shouldReceive('deployedUserId->id')
                ->withNoArgs()
                ->andReturn($iDeployedUserId)
                ->once();

            $process
                ->shouldReceive('isSuccessful')
                ->withNoArgs()
                ->andReturn($processIsSuccessful)
                ->once();
        } else {
            $this->deploymentService
                ->shouldReceive('getDeploymentById')
                ->with($iProjectId, $iDeploymentId)
                ->andReturn($deployment)
                ->twice();

            $this->settingService
                ->shouldReceive('getMailSetting')
                ->withNoArgs()
                ->andReturn($mailSetting)
                ->once();

            $this->userService
                ->shouldReceive('getUserById')
                ->with($iDeployedUserId)
                ->andReturn($deployedUser)
                ->once();

            $this->notifier
                ->shouldReceive('to')
                ->with($emailNotificationRecipient)
                ->andReturn($this->notifier)
                ->once();
            $this->notifier
                ->shouldReceive('notify')
                ->with($emailNotificationSubject, $emailNotidicationMessage)
                ->andReturn($this->notifier)
                ->once();

            ViewFacade::shouldReceive('make')
                ->with('emails.notification')
                ->andReturn($emailNotificationView)
                ->once();

            $project
                ->shouldReceive('emailNotificationRecipient')
                ->withNoArgs()
                ->andReturn($emailNotificationRecipient)
                ->twice();
            $project
                ->shouldReceive('name')
                ->withNoArgs()
                ->andReturn($projectName)
                ->once();

            $deployment
                ->shouldReceive('projectId->id')
                ->withNoArgs()
                ->andReturn($iProjectId)
                ->twice();
            $deployment
                ->shouldReceive('deploymentId->id')
                ->withNoArgs()
                ->andReturn($iDeploymentId)
                ->times(3);
            $deployment
                ->shouldReceive('deployedUserId->id')
                ->withNoArgs()
                ->andReturn($iDeployedUserId)
                ->times(3);

            if (is_null($mailSettingFrom['address']) && is_null($mailSettingFrom['name'])) {
                $mailSetting
                    ->shouldReceive('from')
                    ->withNoArgs()
                    ->andReturn($mailSettingFrom)
                    ->twice();
            } elseif (is_null($mailSettingFrom['address'])) {
                $mailSetting
                    ->shouldReceive('from')
                    ->withNoArgs()
                    ->andReturn($mailSettingFrom)
                    ->times(3);
            } elseif (is_null($mailSettingFrom['name'])) {
                $mailSetting
                    ->shouldReceive('from')
                    ->withNoArgs()
                    ->andReturn($mailSettingFrom)
                    ->times(3);
            } else {
                $mailSetting
                    ->shouldReceive('from')
                    ->withNoArgs()
                    ->andReturn($mailSettingFrom)
                    ->times(4);
            }
            $mailSetting
                ->shouldReceive('driver->value')
                ->withNoArgs()
                ->andReturn($mailSettingDriver)
                ->once();
            $mailSetting
                ->shouldReceive('smtpHost')
                ->withNoArgs()
                ->andReturn($mailSettingSmtpHost)
                ->once();
            $mailSetting
                ->shouldReceive('smtpPort')
                ->withNoArgs()
                ->andReturn($mailSettingSmtpPort)
                ->once();
            $mailSetting
                ->shouldReceive('smtpEncryption->value')
                ->withNoArgs()
                ->andReturn($mailSettingSmtpEncryption)
                ->once();
            $mailSetting
                ->shouldReceive('smtpUserName')
                ->withNoArgs()
                ->andReturn($mailSettingSmtpUserName)
                ->once();
            $mailSetting
                ->shouldReceive('smtpPassword')
                ->withNoArgs()
                ->andReturn($mailSettingSmtpPassword)
                ->once();
            $mailSetting
                ->shouldReceive('sendmailPath')
                ->withNoArgs()
                ->andReturn($mailSettingSendmailPath)
                ->once();

            $process
                ->shouldReceive('isSuccessful')
                ->withNoArgs()
                ->andReturn($processIsSuccessful)
                ->twice();

            $emailNotificationView
                ->shouldReceive('with')
                ->with('project', $project)
                ->andReturn($emailNotificationView)
                ->once();
            $emailNotificationView
                ->shouldReceive('with')
                ->with('deployment', \Hamcrest\Matchers::equalTo($deploymentPresenter))
                ->andReturn($emailNotificationView)
                ->once();
            $emailNotificationView
                ->shouldReceive('with')
                ->with('deployedUser', $deployedUser)
                ->andReturn($emailNotificationView)
                ->once();
            $emailNotificationView
                ->shouldReceive('render')
                ->withNoArgs()
                ->andReturn($emailNotidicationMessage)
                ->once();
        }

        $this->deployerService->runDeployer($iProjectId, $iDeploymentId);

        $this->assertTrue(true);
    }
}
