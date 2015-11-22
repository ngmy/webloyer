<?php

use Tests\Helpers\Factory;

class DeploymentsControllerTest extends TestCase
{
    use Tests\Helpers\ControllerTestHelper;

    use Tests\Helpers\MockeryHelper;

    protected $mockProjectRepository;

    protected $mockDeploymentRepository;

    protected $mockDeploymentForm;

    public function setUp()
    {
        parent::setUp();

        Session::start();

        $user = $this->mockPartial('App\Models\User');
        $user->shouldReceive('can')
            ->andReturn(true);
        $this->auth($user);

        $this->mockProjectRepository = $this->mock('App\Repositories\Project\ProjectInterface');
        $this->mockDeploymentRepository = $this->mock('App\Repositories\Deployment\DeploymentInterface');
        $this->mockDeploymentForm = $this->mock('App\Services\Form\Deployment\DeploymentForm');
        $this->mockProjectModel = $this->mockPartial('App\Models\Project');
        $this->mockDeploymentModel = $this->mockPartial('App\Models\Deployment');
    }

    public function test_Should_DisplayIndexPage_When_IndexPageIsRequested()
    {
        $project = Factory::build('App\Models\Project', [
            'id'         => 1,
            'name'       => 'Project 1',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $deployments = Factory::buildList('App\Models\Deployment', [
            ['id' => 1, 'project_id' => 1, 'number' => 1, 'task' => 'deploy', 'user_id' => 1, 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon, 'user' => new App\Models\User],
            ['id' => 2, 'project_id' => 1, 'number' => 2, 'task' => 'deploy', 'user_id' => 1, 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon, 'user' => new App\Models\User],
            ['id' => 3, 'project_id' => 1, 'number' => 3, 'task' => 'deploy', 'user_id' => 1, 'created_at' => new Carbon\Carbon, 'updated_at' => new Carbon\Carbon, 'user' => new App\Models\User],
        ]);

        $perPage = 10;

        $this->mockDeploymentRepository
            ->shouldReceive('byProjectId')
            ->once()
            ->andReturn(new Illuminate\Pagination\Paginator($deployments, $perPage));

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
        $project = Factory::build('App\Models\Project', [
            'id'         => 1,
            'name'       => 'Project 1',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
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
        $project = Factory::build('App\Models\Project', [
            'id'         => 1,
            'name'       => 'Project 1',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $deployment = Factory::build('App\Models\Deployment', [
            'id'         => 1,
            'project_id' => 1,
            'number'     => 1,
            'task'       => 'deploy',
            'user_id'    => 1,
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
            'user'       => new App\Models\User,
        ]);

        $this->mockDeploymentRepository
            ->shouldReceive('byProjectIdAndNumber')
            ->once()
            ->andReturn($deployment);

        $this->get('projects/1/deployments/1');

        $this->assertResponseOk();
        $this->assertViewHas('deployment');
    }

    public function test_Should_DisplayNotFoundPage_When_ShowPageIsRequestedAndResourceIsNotFound()
    {
        $project = Factory::build('App\Models\Project', [
            'id'         => 1,
            'name'       => 'Project 1',
            'created_at' => new Carbon\Carbon,
            'updated_at' => new Carbon\Carbon,
        ]);

        $this->mockProjectRepository
            ->shouldReceive('byId')
            ->once()
            ->andReturn($project);

        $this->mockDeploymentRepository
            ->shouldReceive('byProjectIdAndNumber')
            ->once()
            ->andReturn(null);

        $this->get('projects/1/deployments/1');

        $this->assertResponseStatus(404);
    }
}
