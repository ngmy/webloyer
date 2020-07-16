<?php

namespace Tests\Http\Controllers\Webhook\Github\V1;

use App\Http\Middleware\ApplySettings;
use App\Http\Middleware\VerifyGithubWebhookSecret;
use Carbon\Carbon;
use Illuminate\Support\MessageBag;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\Webloyer\Application\Deployment\DeploymentService;
use Ngmy\Webloyer\Webloyer\Application\Project\ProjectService;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Deployment;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\DeploymentId;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Status;
use Ngmy\Webloyer\Webloyer\Domain\Model\Deployment\Task;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\Project;
use Ngmy\Webloyer\Webloyer\Domain\Model\Project\ProjectId;
use Ngmy\Webloyer\Webloyer\Domain\Model\User\UserId;
use Ngmy\Webloyer\Webloyer\Port\Adapter\Form\DeploymentForm\DeploymentForm;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\Helpers\MockeryHelper;
use TestCase;

class DeploymentsControllerTest extends TestCase
{
    use ControllerTestHelper;

    use MockeryHelper;

    private $deploymentForm;

    private $deploymentService;

    public function setUp()
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());
        $this->app->instance(VerifyGithubWebhookSecret::class, new DummyMiddleware());

        Session::start();

        $user = $this->mock(User::class);
        $user->shouldReceive('getAuthIdentifier');
        $user->shouldReceive('can')->andReturn(true);
        $user->shouldReceive('name');
        $this->auth($user);

        $this->deploymentForm = $this->mock(DeploymentForm::class);
        $this->deploymentService = $this->mock(DeploymentService::class);
        $this->projectService = $this->mock(ProjectService::class);

        $this->app->instance(DeploymentForm::class, $this->deploymentForm);
        $this->app->instance(DeploymentService::class, $this->deploymentService);
        $this->app->instance(ProjectService::class, $this->projectService);
    }

    public function test_Should_ReturnStatusCode200_When_StoreProcessSucceeds()
    {
        $project = $this->createProject();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn($project)
            ->once();

        $this->deploymentService
            ->shouldReceive('getLastDeployment')
            ->with($project->projectId()->id())
            ->andReturn($this->createDeployment())
            ->once();

        $this->deploymentForm
            ->shouldReceive('save')
            ->andReturn(true)
            ->once();

        $response = $this->post("webhook/github/v1/projects/{$project->projectId()->id()}/deployments");

        $response->assertStatus(200);
    }

    public function test_Should_ReturnStatusCode400_When_StoreProcessFails()
    {
        $project = $this->createProject();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn($project)
            ->once();

        $this->deploymentForm
            ->shouldReceive('save')
            ->andReturn(false)
            ->once();

        $this->deploymentForm
            ->shouldReceive('errors')
            ->withNoArgs()
            ->andReturn(new MessageBag())
            ->once();

        $response = $this->post("webhook/github/v1/projects/{$project->projectId()->id()}/deployments");

        $response->assertStatus(400);
    }

    private function createDeployment(array $params = [])
    {
        $projectId = 1;
        $deploymentId = 1;
        $task = 'deploy';
        $status = 2;
        $message = null;
        $deployedUserId = 1;
        $createdAt = null;
        $updatedAt = null;

        extract($params);

        $deployment = $this->mock(Deployment::class);

        $deployment->shouldReceive('projectId')->andReturn(new ProjectId($projectId));
        $deployment->shouldReceive('deploymentId')->andReturn(new DeploymentId($deploymentId));
        $deployment->shouldReceive('task')->andReturn(new Task($task));
        $deployment->shouldReceive('status')->andReturn(new Status($status));
        $deployment->shouldReceive('message')->andReturn($message);
        $deployment->shouldReceive('deployedUserId')->andReturn(new UserId($deployedUserId));
        $deployment->shouldReceive('createdAt')->andReturn(new Carbon($createdAt));
        $deployment->shouldReceive('updatedAt')->andReturn(new Carbon($updatedAt));

        return $deployment;
    }

    private function createProject(array $params = [])
    {
        $projectId = 1;
        $name = '';
        $githubWebhookExecuteUserId = 1;
        $githubWebhooKSecret = '';

        extract($params);

        $project = $this->mock(Project::class);

        $project->shouldReceive('projectId')->andReturn(new ProjectId($projectId));
        $project->shouldReceive('name')->andReturn($name);
        $project->shouldReceive('githubWebhookExecuteUserId')->andReturn(new UserId($githubWebhookExecuteUserId));
        $project->shouldReceive('githubWebhookSecret')->andReturn($githubWebhooKSecret);

        return $project;
    }
}
