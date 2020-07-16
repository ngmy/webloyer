<?php

namespace App\Http\Controllers;

use App\Http\Middleware\ApplySettings;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Ngmy\Webloyer\IdentityAccess\Application\User\UserService;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\User;
use Ngmy\Webloyer\IdentityAccess\Domain\Model\User\UserId as IdentityAccessUserId;
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

    private $userService;

    private $projectService;

    public function setUp()
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());

        Session::start();

        $user = $this->mock(User::class);
        $user->shouldReceive('can')->andReturn(true);
        $user->shouldReceive('name');
        $user->shouldReceive('userId')->andReturn(new IdentityAccessUserId(1));
        $this->auth($user);

        $this->deploymentForm = $this->mock(DeploymentForm::class);
        $this->deploymentService = $this->mock(DeploymentService::class);
        $this->userService = $this->mock(UserService::class);
        $this->projectService = $this->mock(ProjectService::class);

        $this->app->instance(DeploymentForm::class, $this->deploymentForm);
        $this->app->instance(DeploymentService::class, $this->deploymentService);
        $this->app->instance(UserService::class, $this->userService);
        $this->app->instance(ProjectService::class, $this->projectService);
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->closeMock();
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $project = $this->createProject();
        $deployment = $this->createDeployment([
            'projectId' => $project->projectId()->id(),
        ]);
        $deployments = new Collection([
            $deployment,
        ]);
        $page = 1;
        $perPage = 10;

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn($project)
            ->once();

        $this->deploymentService
            ->shouldReceive('getDeploymentsByPage')
            ->with($project->projectId()->id(), $page, $perPage)
            ->andReturn(
                new LengthAwarePaginator(
                    $deployments,
                    $deployments->count(),
                    $perPage,
                    $page,
                    [
                        'path' => Paginator::resolveCurrentPath(),
                    ]
                )
            )
            ->once();

        foreach ($deployments as $deployment) {
            $user = $this->createUser([
                'userId' => $deployment->deployedUserId()->id(),
            ]);
            $this->userService
                ->shouldReceive('getUserById')
                ->with($deployment->deployedUserId()->id())
                ->andReturn($user)
                ->once();
        }

        $response = $this->get("projects/{$project->projectId()->id()}/deployments");

        $response->assertStatus(200);
        $response->assertViewHas('deployments');
        $response->assertViewHas('project');
        $response->assertViewHas('deployedUsers');
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
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

        $response = $this->post("projects/{$project->projectId()->id()}/deployments");

        $response->assertRedirect("projects/{$project->projectId()->id()}/deployments");
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessFails()
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

        $response = $this->post("projects/{$project->projectId()->id()}/deployments");

        $response->assertRedirect("projects/{$project->projectId()->id()}/deployments");
        $response->assertSessionHasErrors();
    }

    public function test_Should_DisplayShowPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $project = $this->createProject();
        $deployment = $this->createDeployment([
            'projectId' => $project->projectId()->id(),
        ]);

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn($project)
            ->once();

        $this->deploymentService
            ->shouldReceive('getDeploymentById')
            ->with($project->projectId()->id(), $deployment->deploymentId()->id())
            ->andReturn($deployment)
            ->once();

        $this->userService
            ->shouldReceive('getUserById')
            ->with($deployment->deployedUserId()->id())
            ->andReturn($this->createUser([
                'userId' => $deployment->deployedUserId()->id(),
            ]))
            ->once();

        $response = $this->get("projects/{$project->projectId()->id()}/deployments/{$deployment->deploymentId()->id()}");

        $response->assertStatus(200);
        $response->assertViewHas('deployment');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndProjectIsNotFound()
    {
        $project = $this->createProject();
        $deployment = $this->createDeployment();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->get("projects/{$project->projectId()->id()}/deployments/{$deployment->deploymentId()->id()}");

        $response->assertStatus(404);
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndDeploymentIsNotFound()
    {
        $project = $this->createProject();
        $deployment = $this->createDeployment();

        $this->projectService
            ->shouldReceive('getProjectById')
            ->with($project->projectId()->id())
            ->andReturn($project)
            ->once();

        $this->deploymentService
            ->shouldReceive('getDeploymentById')
            ->with($project->projectId()->id(), $deployment->deploymentId()->id())
            ->andReturn(null)
            ->once();

        $response = $this->get("projects/{$project->projectId()->id()}/deployments/{$deployment->deploymentId()->id()}");

        $response->assertStatus(404);
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

        extract($params);

        $project = $this->mock(Project::class);

        $project->shouldReceive('projectId')->andReturn(new ProjectId($projectId));
        $project->shouldReceive('name')->andReturn($name);

        return $project;
    }

    private function createUser(array $params = [])
    {
        $userId = 1;
        $name = '';
        $email = '';

        extract($params);

        $user = $this->mock(User::class);

        $user->shouldReceive('userId')->andReturn(new IdentityAccessUserId($userId));
        $user->shouldReceive('name')->andReturn($name);
        $user->shouldReceive('email')->andReturn($email);

        return $user;
    }
}
