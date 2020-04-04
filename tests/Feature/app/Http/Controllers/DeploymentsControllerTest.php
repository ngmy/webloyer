<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Http\Middleware\ApplySettings;
use App\Models\Deployment;
use App\Models\Project;
use App\Models\User;
use App\Repositories\Project\ProjectInterface;
use App\Services\Form\Deployment\DeploymentForm;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Session;
use Tests\Helpers\ControllerTestHelper;
use Tests\Helpers\DummyMiddleware;
use Tests\Helpers\Factory;
use Tests\TestCase;

class DeploymentsControllerTest extends TestCase
{
    use ControllerTestHelper;

    protected $mockProjectRepository;

    protected $mockDeploymentForm;

    protected $mockProjectModel;

    protected $mockDeploymentModel;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->instance(ApplySettings::class, new DummyMiddleware());

        Session::start();

        $user = $this->partialMock(User::class);
        $user->shouldReceive('can')
            ->andReturn(true);
        $this->auth($user);

        $this->mockProjectRepository = $this->mock(ProjectInterface::class);
        $this->mockDeploymentForm = $this->mock(DeploymentForm::class);
        $this->mockProjectModel = $this->partialMock(Project::class);
        $this->mockDeploymentModel = $this->partialMock(Deployment::class);
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $deployments = Factory::buildList(Deployment::class, [
            ['id' => 1, 'project_id' => 1, 'number' => 1, 'task' => 'deploy', 'user_id' => 1, 'created_at' => new Carbon(), 'updated_at' => new Carbon(), 'user' => new User()],
            ['id' => 2, 'project_id' => 1, 'number' => 2, 'task' => 'deploy', 'user_id' => 1, 'created_at' => new Carbon(), 'updated_at' => new Carbon(), 'user' => new User()],
            ['id' => 3, 'project_id' => 1, 'number' => 3, 'task' => 'deploy', 'user_id' => 1, 'created_at' => new Carbon(), 'updated_at' => new Carbon(), 'user' => new User()],
        ]);

        $perPage = 10;

        $project = $this->mockProjectModel
            ->shouldReceive('getDeploymentsByPage')
            ->once()
            ->andReturn(new Paginator($deployments, $perPage))
            ->mock();

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->get('projects/1/deployments');

        $this->assertResponseOk();
        $this->assertViewHas('deployments');
        $this->assertViewHas('project');
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessSucceeds()
    {
        $project = $this->mockProjectModel
            ->shouldReceive('getLastDeployment')
            ->once()
            ->andReturn($this->mockDeploymentModel)
            ->mock();

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockDeploymentForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        $params = [
            'project_id' => 1,
            'user_id'    => 1,
        ];

        $this->post('projects/1/deployments', $params);

        $this->assertRedirectedToRoute('projects.deployments.index', [$project]);
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessFails()
    {
        $project = Factory::build(Project::class, [
            'id'         => 1,
            'name'       => 'Project 1',
            'created_at' => new Carbon(),
            'updated_at' => new Carbon(),
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockDeploymentForm
            ->shouldReceive('save')
            ->once()
            ->andReturn(false);

        $this->mockDeploymentForm
            ->shouldReceive('errors')
            ->once()
            ->andReturn([]);

        $params = [
            'project_id' => 1,
            'user_id'    => 1,
        ];

        $this->post('projects/1/deployments', $params);

        $this->assertRedirectedToRoute('projects.deployments.index', [$project]);
        $this->assertSessionHasErrors();
    }

    public function test_Should_DisplayShowPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $deployment = Factory::build(Deployment::class, [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new Carbon(),
            'updated_at' => new Carbon(),
            'user'       => new User(),
        ]);

        $project = $this->mockProjectModel
            ->shouldReceive('getDeploymentByNumber')
            ->once()
            ->andReturn($deployment)
            ->mock();

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->get('projects/1/deployments/1');

        $this->assertResponseOk();
        $this->assertViewHas('deployment');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $project = $this->mockProjectModel
            ->shouldReceive('getDeploymentByNumber')
            ->once()
            ->andReturn(null)
            ->mock();

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->get('projects/1/deployments/1');

        $this->assertResponseStatus(404);
    }
}
