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
        $user->shouldReceive('hasPermission')
            ->andReturn(true);
        $this->auth($user);

        $this->mockProjectRepository = $this->mock(ProjectInterface::class);
        $this->mockDeploymentForm = $this->mock(DeploymentForm::class);
        $this->mockProjectModel = $this->partialMock(Project::class);
        $this->mockDeploymentModel = $this->partialMock(Deployment::class);
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $deployments = factory(Deployment::class, 3)->make();

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

        $response = $this->get('projects/1/deployments');

        $response->assertStatus(200);
        $response->assertViewHas('deployments');
        $response->assertViewHas('project');
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

        $response = $this->post('projects/1/deployments', $params);

        $response->assertRedirect('projects/1/deployments');
    }

    public function test_Should_RedirectToIndexPage_When_StoreProcessFails()
    {
        $project = factory(Project::class)->make();

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

        $response = $this->post('projects/1/deployments', $params);

        $response->assertRedirect('projects/1/deployments');
        $response->assertSessionHasErrors();
    }

    public function test_Should_DisplayShowPage_When_ShowPageIsRequestedAndResourceIsFound()
    {
        $deployment = factory(Deployment::class)->make();

        $project = $this->mockProjectModel
            ->shouldReceive('getDeploymentByNumber')
            ->once()
            ->andReturn($deployment)
            ->mock();

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $response = $this->get('projects/1/deployments/1');

        $response->assertStatus(200);
        $response->assertViewHas('deployment');
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

        $response = $this->get('projects/1/deployments/1');

        $response->assertStatus(404);
    }
}
