<?php

namespace Ngmy\Webloyer\Webloyer\Application\Deployer;

use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\Common\Notification\NotifierInterface;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Application\Deployer\DeployerService;
use Ngmy\Webloyer\Webloyer\Application\Recipe\RecipeService;
use Ngmy\Webloyer\Webloyer\Application\Server\ServerService;
use Ngmy\Webloyer\Webloyer\Application\Setting\SettingService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\Recipe;
use Ngmy\Webloyer\Webloyer\Domain\Model\Recipe\RecipeId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\Server;
use Ngmy\Webloyer\Webloyer\Domain\Model\Server\ServerId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFile;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerFileDirector;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerRecipeFileBuilder;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployer\DeployerServerListFileBuilder;
use Ngmy\Webloyer\Webloyer\Domain\Service\Deployer\DeployerDispatcherServiceInterface;
use Symfony\Component\Process\ProcessBuilder;
use Tests\Helpers\MockeryHelper;
use TestCase;

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

    public function test_Should_RunDeployer()
    {
        $iProjectId = 1;
        $iDeploymentId = 1;
        $iRecipeId = 1;
        $iServerId = 1;
        $deployment = $this->mock(Deployment::class);
        $project = $this->mock(Project::class);
        $server = $this->mock(Server::class);
        $serverId = new ServerId($iServerId);
        $recipe = $this->mock(Recipe::class);
        $recipeId = new RecipeId($iRecipeId);
        $recipeIds = [$recipeId];
        $serverListFile = $this->mock(DeployerFile::class);
        $recipeFile = $this->mock(DeployerFile::class);

        $project->shouldReceive('serverId->id')
            ->withNoArgs()
            ->andReturn($iServerId)
            ->once();
        $project->shouldReceive('recipeIds')
            ->withNoArgs()
            ->andReturn($recipeIds)
            ->once();

        $this->deploymentService
            ->shouldReceive('getDeploymentById')
            ->with($iProjectId, $iDeploymentId)
            ->andReturn($deployment)
            ->once();
        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($iProjectId)
            ->andReturn($project)
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
            ->andReturn($serverListFile, $recipeFile)
            ->times(1 + count($recipeIds));

        $this->deployerService->runDeployer($iProjectId, $iDeploymentId);

        $this->assertTrue(true);
    }
}
